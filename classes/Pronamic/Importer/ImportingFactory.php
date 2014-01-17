<?php

class Pronamic_Importer_ImportingFactory {
	
	public static $imports = array();
    public static $configs = array();
	
	public static function register( $class_name, Pronamic_Importer_Config $config = null ) {
		if ( ! in_array( $class_name, self::$imports ) )
			self::$imports[] = $class_name;
        
        if ( ! array_key_exists( $class_name, self::$configs ) )
            self::$configs[$class_name] = $config;
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
    
    public static function has_config( $class_name ) {
        return ( array_key_exists( $class_name, self::$configs ) );
    }
    
    /**
     * 
     * @param string $class_name
     * @return Pronamic_Importer_Config
     */
    public static function get_config( $class_name ) {
        if ( array_key_exists( $class_name, self::$configs ) )
            return self::$configs[$class_name];
    }
	
	public static function all() {
		return self::$imports;
	}
}