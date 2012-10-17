<?php
/*
Plugin Name: Pronamic Importer
Description: Import posts, pages, comments, custom fields, categories, tags and more from the Horses database.
Author: pronamic
Author URI: http://pronamic.eu/
Version: 0.1
Text Domain: pronamic_importer
*/

ini_set( 'max_execution_time', 3600 ); // 300 seconds = 5 minutes


function pronamic_importer_autoload($name) {
	$file = __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $name . '.php';
	$file = str_replace( '\\', DIRECTORY_SEPARATOR, $file );

	if ( is_readable( $file ) ) {
		require_once $file;
	}
}

spl_autoload_register( 'pronamic_importer_autoload' );


//

function pronamic_importer_create($pdo) {
	$importer = new Importer();

	$importer->next(new ExecuteQuery($pdo, 'UPDATE nieuws SET wordpress_import_attempts = wordpress_import_attempts + 1 WHERE nws_id = :import_id;' ) );

	$importer->next(new CreatePhpQueryFromPostContent());

	$importer->next(new SetPostStatus('publish'));

	$importer->next(new SetMetaImportUrl('_import_url'));

	$importer->next(new SetDateIfNotSet());
	$importer->next(new SetPostDate());

	$importer->next(new ConvertRelativeUrlToAbsolute('img', 'src'));
	$importer->next(new ConvertRelativeUrlToAbsolute('a', 'href'));

	$importer->next(new SetPostContent());
	
	$importer->next(new RemoveElements('p:empty'));

	$importer->next(new FindPostThumbnail('this:has(img):first'));
	
	$importer->next(new FindImagesInContent());

	$importer->next(new SetPostThumbnailIfNotSet());

	$importer->next(new DownloadMedia());
	
	$importer->next(new InsertPost());
	$importer->next(new InsertAttachments());

	$importer->next(new UpdateImageSource());
	$importer->next(new UpdateFileLink());

	$importer->next(new SetPostContent());
	
	$importer->next(new TidyContent(array('css-prefix' => 'pronamic-import')));

	$importer->next(new TrimContent());

	$importer->next(new UpdatePost());
	
	$importer->next(new AddPostMeta());
	$importer->next(new SetPostTerms());
	
	$importer->next(new InsertComments());

	$importer->next(new DeleteTemporaryFiles());
	
	$importer->next(new VarDumpImport());

	$importer->next(new ExecuteQuery($pdo, 'UPDATE nieuws SET wordpress_imported = TRUE WHERE nws_id = :import_id;'));

	$importer->next(new Done());

	return $importer;
}

function pronamic_importer_get_import_info_from_id($pdo, $id) {
	$legacyDataRepository = new Pronamic_Importer_Data($pdo);

	$importInfo = new ImportInfo();

	$content = $legacyDataRepository->get_post_by_id($id);

	$url = sprintf(
		'http://www.bakkeveen.nl/item/%d/%s.html' ,
		$content->import_id ,
		sanitize_title( $content->import_title )
	);

	$importInfo->setUrl($url);

	$importInfo->setPostData( 'post_title', $content->import_title );
	$importInfo->setPostData( 'post_content', $content->import_content );
	$importInfo->setPostData( 'post_type', $content->import_type );
	
	$timezoneUtc = new DateTimeZone( 'UTC' );
	$timezoneAms = new DateTimeZone( 'Europe/Amsterdam' );
	
	$date = new DateTime( '@' . $content->import_date, $timezoneUtc );
	$date->setTimezone( $timezoneAms );

	$importInfo->setDate( $date );

	$importInfo->setPostMeta( '_import_id', $content->import_id );
	$importInfo->setPostMeta( '_import_category_id', $content->import_category_id );
	$importInfo->setPostMeta( '_import_author_id', $content->import_author_id );

	$importInfo->addTerm( new TermInfo( 'category', $content->import_category_name ) );

	$images = $legacyDataRepository->get_images_for_post_id( $content->import_id );
	foreach ( $images as $image ) {
		$media = new ImportInfo( $image->import_url );
		$media->setPostData( 'post_content', $image->import_content );
		$media->setPostMeta( '_import_id',   $image->import_id );
		$media->setPostMeta( '_import_url',  $image->import_url );

		$importInfo->addMedia( $media );
	}

	return $importInfo;
}

function pronamic_importer_try_import() {
	$pdo = Pronamic_Importer_Plugin::get_database();

	$importer = pronamic_importer_create( $pdo );
	
	if ( isset( $_POST['import-bulk'] ) ) {
		$ids = filter_input( INPUT_POST, 'ids', FILTER_SANITIZE_STRING, array( 'flags' => FILTER_REQUIRE_ARRAY ) );
		
		foreach ( $ids as $id ) {
			$importInfo = pronamic_importer_get_import_info_from_id( $pdo, $id );

			$importer->start( $importInfo );
		}
	}
}

/**
 * Pronamic database importer plugin
 * 
 * @author Remco
 */
class Pronamic_Importer_Plugin {
	public static $file;
	
	public static $dirname;

	/**
	 * Bootstrap the plugin
	 */
	public static function bootstrap( $file ) {
		self::$file    = $file;
		self::$dirname = dirname( $file );

		add_action( 'init',               array( __CLASS__, 'init' ) );

		add_action( 'admin_init',         array( __CLASS__, 'admin_init' ) );
		add_action( 'admin_menu',         array( __CLASS__, 'admin_menu' ) );

		add_action( 'add_meta_boxes',     array( __CLASS__, 'add_meta_boxes' ) );

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );

		add_filter( 'the_content',        array( __CLASS__, 'the_content' ) );
	}

	/**
	 * Initialize
	 */
	public static function init() {
		load_plugin_textdomain( 'pronamic_importer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		// Requirements

		// WordPress import - http://codex.wordpress.org/Function_Reference/register_importer
		require_once ABSPATH . '/wp-admin/includes/import.php';
		require_once ABSPATH . '/wp-admin/includes/class-wp-importer.php';
		// Pronamic
		require_once self::$dirname . '/classes/Pronamic/Importer/Importer.php';
		require_once self::$dirname . '/classes/Pronamic/Importer/Data.php';
		// phpQuery - http://code.google.com/p/phpquery/
		require_once self::$dirname . '/includes/phpQuery/phpQuery/phpQuery.php';
		// UrlToAbsolute - http://sourceforge.net/projects/absoluteurl
		require_once self::$dirname . '/includes/AbsoluteUrl/url_to_absolute.php';
	}

	/**
	 * Admin initialize
	 */
	public static function admin_init() {
		$GLOBALS['pronamic_importer'] = $importer = new Pronamic_Importer_Importer();

		// Register importer
		register_importer(
			'pronamic_importer',
			__( 'Pronamic Importer', 'pronamic_importer' ),
			__( 'Import <strong>posts, pages, comments, custom fields, categories, and tags</strong>.', 'pronamic_importer' ),
			array( $importer, 'dispatch' ) 
		);		

		// Register settings
		register_setting( 'pronamic_importer', 'pronamic_importer_db_name' );
		register_setting( 'pronamic_importer', 'pronamic_importer_db_user' );
		register_setting( 'pronamic_importer', 'pronamic_importer_db_password' );
		register_setting( 'pronamic_importer', 'pronamic_importer_db_host' );
	}

	/**
	 * Admin menu
	 */
	public static function admin_menu() {
		add_management_page( 
			__( 'Database Import', 'pronamic_importer' ), // page_title
			__( 'Database Import', 'pronamic_importer' ), // menu_title
			'manage_options', // capability
			'pronamic_importer', // menu_slug
			array( __CLASS__, 'page_importer' ) // function
		);
	}

	/**
	 * Page importer
	 */
	public static function page_importer() {
		include 'admin/import.php';
	}

	/**
	 * Get database
	 */
	public static function get_database() {
		global $pronamic_importer_db;

		if ( ! isset( $pronamic_importer_db ) ) {
			$name     = get_option( 'pronamic_importer_db_name' );
			$user     = get_option( 'pronamic_importer_db_user' );
			$password = get_option( 'pronamic_importer_db_password' );
			$host     = get_option( 'pronamic_importer_db_host' );

			$dsn = sprintf( 
				'mysql:dbname=%s;host=%s;charset=UTF-8',
				$name,
				$host
			);

			$pronamic_importer_db = new PDO( $dsn, $user, $password, array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' ) );
		}

		return $pronamic_importer_db;
	}

	/**
	 * Add meta boxes
	 */
	public static function add_meta_boxes() {
		$post_types = get_post_types();

		foreach ( $post_types as $post_type ) {
			add_meta_box( 
				'pronamic_importer_meta_box', // id
				__( 'Import Information', 'pronamic_importer' ), // title
				array( __CLASS__, 'meta_box_import' ), // callback
        		$post_type // post_type
    		);
		}
	}

	/**
	 * Meta box import
	 */
	public static function meta_box_import() {
		include 'admin/meta-box-import.php';
	}

	/**
	 * Enqueue scripts
	 */
	public static function enqueue_scripts() {
		wp_enqueue_style( 'pronamic-importer', plugins_url( 'includes/css/site.css', __FILE__ ) );
	}

	/**
	 * The content
	 * 
	 * @param string $content
	 * @return string
	 */
	public static function the_content( $content ) {
		global $post, $more;
	
		if ( $more ) {
			ob_start();

			include 'includes/import-information.php';

			$out = ob_get_clean();

			$content .= $out;
		}
	
		return $content;
	}
}

Pronamic_Importer_Plugin::bootstrap( __FILE__ );
