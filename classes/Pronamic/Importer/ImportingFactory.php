<?php

class Pronamic_Importer_ImportingFactory {
	
	public static $imports = array();
	
	public static function register( $class_name ) {
		if ( ! in_array( $class_name, self::$imports ) )
			self::$imports[] = $class_name;
	}
	
	/**
	 * 
	 * @param type $class_name
	 * @return Importing
	 */
	public static function get( $class_name ) {
		if ( in_array( $class_name, self::$imports ) )
			return new $class_name( Pronamic_Importer_Plugin::get_database() );
	}
	
	public static function all() {
		return self::$imports;
	}
}