<?php

/**
 * Class to handle the config settings for the table name, primary key column 
 * name and the chosen database configuration.
 * 
 * This makes it easier to assign the values to a specific view, sent as part of the
 * register call to the factory
 */
class Pronamic_Importer_Config {
    
    private $table_name;
    private $pk_column;
    
    /**
     * @var Pronamic_Importer_Database
     */
    private $database;
    
    public function __construct( $table_name, $pk_column ) {
        $this->table_name = $table_name;
        $this->pk_column = $pk_column;
    }
    
    public function set_database( Pronamic_Importer_Database $database ) {
        $this->database = $database;
    }
    
    public function get_table_name() {
        return $this->table_name;
    }
    
    public function get_pk_column() {
        return $this->pk_column;
    }
}