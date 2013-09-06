<?php
/*
Plugin Name: Pronamic Importer
Description: Import posts, pages, comments, custom fields, categories, tags and more.
Author: pronamic
Author URI: http://pronamic.eu/
Version: 0.1
Text Domain: pronamic_importer
*/

function pronamic_importer_autoload($name) {
	$file = __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $name . '.php';
	$file = str_replace( '\\', DIRECTORY_SEPARATOR, $file );
	
	if ( is_readable( $file ) ) {
		require_once $file;
	}
}

spl_autoload_register( 'pronamic_importer_autoload' );

//

function pronamic_importer_get_import_info_from_id($pdo, $id) {
	$data = new Pronamic_Importer_Data($pdo);

	$import_post = new ImportInfo();

	$query = file_get_contents( Pronamic_Importer_Plugin::$dirname . '/includes/sql/project-3/select-post-by-id.sql' );
	
	$content = $data->get_post_by_id( $query, $id );

	$url = 'http://www.bestelauto.nl/%d-%s.html';

	$url = sprintf(
		$url,
		$content->import_id,
		sanitize_title( $content->import_title )
	);

	$import_post->setUrl( $url );

	$import_post->setPostData( 'post_title', $content->import_title );
	$import_post->setPostData( 'post_content', $content->import_content );
	$import_post->setPostData( 'post_type', $content->import_type );
	
	$date = Pronamic_Importer_Util_DateTime::convert_timestamp( $content->import_date, 'UTC', 'Europe/Amsterdam' );

	$import_post->setDate( $date );

	$import_post->setPostMeta( '_import_id', $content->import_id );
	$import_post->setPostMeta( '_import_author_id', $content->import_author_id );
	
	if ( isset( $content->import_category_id ) ) {
		$import_post->setPostMeta( '_import_category_id', $content->import_category_id );
	}

	if ( !empty( $content->import_category_name ) ) {
		$import_post->addTerm( new TermInfo( 'category', $content->import_category_name ) );
	}

	// Attachments
	$query = file_get_contents( Pronamic_Importer_Plugin::$dirname . '/includes/sql/project-3/select-attachments-by-post-id.sql' );

	$attachments = $data->get_attachments_for_post_id( $query, $content->import_id );
	foreach ( $attachments as $attachment ) {
		$import_attachment = new ImportInfo( $attachment->import_url );
		$import_attachment->setPostData( 'post_content', $attachment->import_content );
		$import_attachment->setPostMeta( '_import_id',   $attachment->import_id );
		$import_attachment->setPostMeta( '_import_url',  $attachment->import_url );

		$import_post->addMedia( $import_attachment );
	}

	// Meta
	$query = file_get_contents( Pronamic_Importer_Plugin::$dirname . '/includes/sql/project-3/select-meta-by-post-id.sql' );

	$meta = $data->get_meta_for_post_id( $query, $content->import_id );
	foreach ( $meta as $meta_data ) {
		$import_post->setPostMeta( $meta_data->meta_key, $meta_data->meta_value );
	}

	// Terms
	$query = file_get_contents( Pronamic_Importer_Plugin::$dirname . '/includes/sql/project-3/select-terms-by-post-id.sql' );

	$terms = $data->get_terms_for_post_id( $query, $content->import_id );
	foreach ( $terms as $term_data ) {
		$import_post->addTerm( new TermInfo( $term_data->taxonomy, $term_data->term ) );
	}

	return $import_post;
}

function pronamic_importer_try_import() {
	$pdo = Pronamic_Importer_Plugin::get_database();
	$view = filter_input( INPUT_GET, 'view', FILTER_SANITIZE_STRING );
	
	$importer = Pronamic_Importer_Importer::get_default_importer( $pdo, 'event', 'id' );

	if ( isset( $_POST['import-bulk'] ) ) {
		$ids = filter_input( INPUT_POST, 'ids', FILTER_SANITIZE_STRING, array( 'flags' => FILTER_REQUIRE_ARRAY ) );
		
		echo '<div style="height: 500px; overflow: auto">';

		$importing = Pronamic_Importer_ImportingFactory::get( $view );
		
		foreach ( $ids as $id ) {
			
			

			$importer->start( $importing->get( $id ) );
		}
		
		echo '</div>';
	}
}

/**
 * Pronamic database importer plugin
 * 
 * @author Remco
 */
class Pronamic_Importer_Plugin {
	/**
	 * Plugin filename
	 * 
	 * @var stromg
	 */
	public static $file;
	
	/**
	 * Plugin dirname
	 * 
	 * @var string
	 */
	public static $dirname;

	//////////////////////////////////////////////////

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

		add_filter( 'the_content',        array( __CLASS__, 'the_content' ), 50 );
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public static function init() {
		load_plugin_textdomain( 'pronamic_importer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		// Time limit
		set_time_limit( 0 );

		// Requirements

		// WordPress import - http://codex.wordpress.org/Function_Reference/register_importer
		require_once ABSPATH . '/wp-admin/includes/import.php';
		require_once ABSPATH . '/wp-admin/includes/class-wp-importer.php';
		// Pronamic
		require_once self::$dirname . '/classes/Pronamic/Importer/Importer.php';
		require_once self::$dirname . '/classes/Pronamic/Importer/ImportingFactory.php';
		require_once self::$dirname . '/classes/Pronamic/Importer/Data.php';
		require_once self::$dirname . '/classes/Pronamic/Importer/Util/DateTime.php';
		require_once self::$dirname . '/classes/Pronamic/Importer/Actions/StripSlashesPostData.php';
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

	//////////////////////////////////////////////////

	/**
	 * Admin menu
	 */
	public static function admin_menu() {
		add_management_page( 
			__( 'Pronamic Import', 'pronamic_importer' ), // page_title
			__( 'Pronamic Import', 'pronamic_importer' ), // menu_title
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

	public static function get_placeholder() {
		return apply_filters( 'pronamic_importer_placeholder_path', plugin_dir_path( self::$file ) . '/includes/placeholder.gif' );
	}
	
	//////////////////////////////////////////////////

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
