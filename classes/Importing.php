<?php

/**
 * Parent class to represent a sub plugin object used to
 * get and show all importing data.
 * 
 * When extended, you must implement the get and view_all
 * methods, and then register the class with the 
 * ImportingFactory.
 * 
 * @author Leon Rowland <leon@rowland.nl>
 */
abstract class Importing {
	
	/**
	 * Holds the PDO object that
	 * is used to import.
	 * @var PDO
	 */
	private $pdo;
	
	/**
	 * Will return an ImportInfo class that has been filled
	 * with the completed data.
	 * 
	 * @access public
	 * @return ImportInfo
	 */
	abstract public function get( $id );
	
	/**
	 * Will return an array of arrays, with how many and whichever keys 
	 * you may want.  The only requirement is that the returned rows
	 * have a key that represent their row ID used in get().
	 * 
	 * @access public
	 * @return array
	 */
	abstract public function get_all( $limit, $offset );
	
	/**
	 * Sets the PDO object to this instance.
	 * 
	 * @param PDO $pdo
	 */
	public function __construct( PDO $pdo ) {
		$this->pdo = $pdo;
	}
	
	/**
	 * Returns the assigned PDO object
	 * 
	 * @access public
	 * @return PDO
	 */
	public function get_pdo() {
		return $this->pdo;
	}
}