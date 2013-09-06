<?php

/**
 * ImportingOutput
 * 
 * Used to represent data that can be output on the admin
 * settings pages.
 * 
 * @package Importer
 */
class ImportingOutput {
	
	/**
	 *
	 * @var int
	 */
	private $ID;
	
	/**
	 *
	 * @var string
	 */
	private $title;
	
	/**
	 *
	 * @var string
	 */
	private $url;
	
	/**
	 *
	 * @var string
	 */
	private $description;
	
	/**
	 *
	 * @var DateTime
	 */
	private $date;
	
	/**
	 *
	 * @var int
	 */
	private $author_id;
	
	/**
	 *
	 * @var string
	 */
	private $post_type;
	
	/**
	 *
	 * @var string
	 */
	private $category;
	
	/**
	 * Shows a checkbox for this output instance.  The name parameter
	 * defaults to 'ids[]'. It would be recommended if building a table, and using
	 * eeach ImportingOutput as a row, that you have a name that is an array ( using [] )
	 * 
	 * @access public
	 * @param string $name
	 */
	public function show_checkbox( $name = "ids[]" ) {
		?>
		<input type="checkbox" name="<?php echo $name; ?>" value="<?php echo $this->get_ID(); ?>"/>
		<?php
	}
	
	/**
	 * =====
	 * 
	 * GETTERS/SETTERS
	 * 
	 * =====
	 */
	
	public function get_ID() {
		return $this->ID;
	}

	public function set_ID( $ID ) {
		$this->ID = $ID;
		return $this;
	}

	public function get_title() {
		return $this->title;
	}

	public function set_title( $title ) {
		$this->title = $title;
		return $this;
	}

	public function get_url() {
		return $this->url;
	}

	public function set_url( $url ) {
		$this->url = $url;
		return $this;
	}

	public function get_description() {
		return $this->description;
	}

	public function set_description( $description ) {
		$this->description = $description;
		return $this;
	}

	public function get_date() {
		return $this->date;
	}

	public function set_date( DateTime $date ) {
		$this->date = $date;
		return $this;
	}

	public function get_author_id() {
		return $this->author_id;
	}

	public function set_author_id( $author_id ) {
		$this->author_id = $author_id;
		return $this;
	}

	public function get_post_type() {
		return $this->post_type;
	}

	public function set_post_type( $post_type ) {
		$this->post_type = $post_type;
		return $this;
	}

	public function get_category() {
		return $this->category;
	}

	public function set_category( $category ) {
		$this->category = $category;
		return $this;
	}


}