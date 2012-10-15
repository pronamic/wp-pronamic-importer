<?php
/*
Plugin Name: Pronamic Database Importer
Description: Import posts, pages, comments, custom fields, categories, tags and more from the Horses database.
Author: pronamic
Author URI: http://pronamic.eu/
Version: 0.1
Text Domain: pronamic_db_importer
*/

ini_set( 'max_execution_time', 3600 ); // 300 seconds = 5 minutes

require_once ABSPATH . 'wp-admin/includes/import.php';
require_once __DIR__ . '/phpQuery/phpQuery/phpQuery.php';
require_once __DIR__ . '/AbsoluteUrl/url_to_absolute.php';

if(!class_exists('WP_Importer')) {
	$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
	if(file_exists($class_wp_importer))
		require $class_wp_importer;
}

function pronamic_db_importer_autoload($name) {
	$file = __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $name . '.php';
	$file = str_replace('\\', DIRECTORY_SEPARATOR, $file);

	if(is_readable($file)) {
		require_once $file;
	}
}

spl_autoload_register('pronamic_db_importer_autoload');

class Pronamic_Database_Importer extends WP_Importer {
	public function dispatch() {
		include 'admin/importer.php';
	}
}

function pronamic_db_importer_init() {
	$GLOBALS['pronamic_db_importer'] = new Pronamic_Database_Importer();

	register_importer(
		'pronamic_db_importer', 
		'Pronamic Database Importer', 
		__('Import <strong>posts, pages, comments, custom fields, categories, and tags</strong> from an custom database.', 'pronamic_db_importer'), array( $GLOBALS['pronamic_db_importer'], 'dispatch' ) );
}

add_action( 'admin_init', 'pronamic_db_importer_init' );

//

class Horses_Legacy_Data {
	private $pdo;

	public function __construct($pdo) {
		$this->pdo = $pdo;		
	}

	public function addImportedColumn() {
		// UPDATE content SET wordpress_imported = FALSE

		$this->pdo->query('ALTER TABLE nieuws ADD wordpress_imported BOOLEAN NOT NULL DEFAULT FALSE;');
		$this->pdo->query('ALTER TABLE nieuws ADD wordpress_import_attempts INT NOT NULL DEFAULT 0;');
		$this->pdo->query('ALTER TABLE nieuws ADD wordpress_failed BOOLEAN NOT NULL DEFAULT FALSE;');
	}

	public function get_post_by_id( $id ) {
		$query = '
			SELECT
				news.nws_id,
				news.nws_title,
				news.nws_url,
				news.nws_descr,
				news.nws_timestamp,
				news.nws_poster,
				news.nws_category,
				category.cat_title
			FROM
				nieuws AS news
					LEFT JOIN
				category AS category
						ON news.nws_category = category.cat_id
			WHERE
				news.nws_id = :id
			;
		';
	
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':id', $id, PDO::PARAM_INT);
		$statement->execute();
	
		$content = $statement->fetchAll(PDO::FETCH_OBJ);
		$content = array_shift($content);
		
		return $content;
	}

	public function get_images_for_post_id( $id ) {
		$query = '
			SELECT
				image.img_id ,  
				image.img_name , 
				image.img_descr
			FROM
				images AS image
			WHERE
				image.img_nws_id = :id
			ORDER BY
				image.img_volgnummer
			;
		';
	
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':id', $id, PDO::PARAM_INT);
		$statement->execute();
	
		$content = $statement->fetchAll(PDO::FETCH_OBJ);

		return $content;
	}
}

function pronamic_db_importer_create($pdo) {
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

function pronamic_db_importer_get_import_info_from_id($pdo, $id) {
	$legacyDataRepository = new Horses_Legacy_Data($pdo);

	$importInfo = new ImportInfo();

	$content = $legacyDataRepository->get_post_by_id($id);

	$url = sprintf(
		'http://www.bakkeveen.nl/item/%d/%s.html' ,
		$content->nws_id ,
		sanitize_title( $content->nws_title )
	);

	$importInfo->setUrl($url);

	$importInfo->setPostData('post_title', $content->nws_title);
	$importInfo->setPostData( 'post_content', $content->nws_descr );
	
	$timezoneUtc = new DateTimeZone('UTC');
	$timezoneAms = new DateTimeZone('Europe/Amsterdam');
	
	$date = new DateTime('@' . $content->nws_timestamp, $timezoneUtc);
	$date->setTimezone($timezoneAms);

	$importInfo->setDate($date);

	$importInfo->setPostMeta('_import_id', $content->nws_id);
	$importInfo->setPostMeta('_import_category_id', $content->nws_category);
	$importInfo->setPostMeta('_import_author_id', $content->nws_poster);

	$importInfo->addTerm(new TermInfo('category', $content->cat_title));

	$images = $legacyDataRepository->get_images_for_post_id( $content->nws_id );
	foreach ( $images as $image ) {
		$url = sprintf(
			'http://www.bakkeveen.nl/i/items/%d.jpg',
			$image->img_id
		);

		$media = new ImportInfo( $url );
		$media->setPostData( 'post_content', $image->img_descr );
		$media->setPostMeta( '_import_url', $url );

		$importInfo->addMedia( $media );
	}

	return $importInfo;
}

function pronamic_db_importer_try_import() {
	$pdo = Pronamic_DatabaseImporter_Plugin::get_database();

	$importer = pronamic_db_importer_create( $pdo );
	
	if(isset($_POST['import-bulk'])) {
		$ids = filter_input(INPUT_POST, 'pronamic_ids', FILTER_SANITIZE_STRING, array('flags' => FILTER_REQUIRE_ARRAY));
		
		foreach($ids as $id) {
			$importInfo = pronamic_db_importer_get_import_info_from_id($pdo, $id);

			$importer->start($importInfo);
		}
	}
}

/**
 * Pronamic database importer plugin
 * 
 * @author Remco
 */
class Pronamic_DatabaseImporter_Plugin {
	/**
	 * Bootstrap the plugin
	 */
	public static function bootstrap() {
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
		load_plugin_textdomain( 'pronamic_db_importer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Admin initialize
	 */
	public static function admin_init() {
		register_setting( 'pronamic_db_importer', 'pronamic_db_importer_name' );
		register_setting( 'pronamic_db_importer', 'pronamic_db_importer_user' );
		register_setting( 'pronamic_db_importer', 'pronamic_db_importer_password' );
		register_setting( 'pronamic_db_importer', 'pronamic_db_importer_host' );
	}

	/**
	 * Admin menu
	 */
	public static function admin_menu() {
		add_management_page( 
			__( 'Database Import', 'pronamic_db_importer' ), // page_title
			__( 'Database Import', 'pronamic_db_importer' ), // menu_title
			'manage_options', // capability
			'pronamic_db_importer', // menu_slug
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
			$name = get_option( 'pronamic_db_importer_name' );
			$user = get_option( 'pronamic_db_importer_user' );
			$password = get_option( 'pronamic_db_importer_password' );
			$host = get_option( 'pronamic_db_importer_host' );

			$dsn = sprintf( 
				'mysql:dbname=%s;host=%s;charset=UTF-8' , 
				$name ,
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
				'pronamic_db_importer_meta_box', // id
				__( 'Import Information', 'pronamic_db_importer' ), // title
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
		wp_enqueue_style( 'pronamic-db-importer', plugins_url( 'includes/css/site.css', __FILE__ ) );
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

Pronamic_DatabaseImporter_Plugin::bootstrap();
