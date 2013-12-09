<?php

class ImportInfo {
	/**
	 * The URL to import
	 * 
	 * @var string
	 */
	private $url;

	////////////////////////////////////////////////////////////

	/**
	 * The date of the content to import
	 * 
	 * @var DateTime
	 */
	private $date;

	////////////////////////////////////////////////////////////

	/**
	 * The phpQuery element
	 * 
	 * @var phpQuery
	 */
	private $phpQuery;

	////////////////////////////////////////////////////////////

	/**
	 * Post array
	 * 
	 * @var array
	 */
	public $post;

	/**
	 * Meta array
	 * 
	 * @var array
	 */
	public $meta;

	/**
	 * Taxonomies array
	 * 
	 * @var array
	 */
	public $taxonomies;

	/**
	 * Media array
	 * 
	 * @var array
	 */
	public $media;

	public $thumbnail;
	
	private $definedThumbnail = false;

	////////////////////////////////////////////////////////////

	public $file;

	public $contentElement;
	
	////////////////////////////////////////////////////////////

	/**
	 * Constructs and initializes an import info object
	 * 
	 * @param string $url
	 */
	public function __construct($url = null) {
		$this->url = $url;

		$this->phpQuery = phpQuery::newDocument();

		$this->post = array();
		$this->meta = array();
		$this->taxonomies = array();
		$this->media = array();
		$this->comments = array();
	}

	////////////////////////////////////////////////////////////

	/**
	 * Check if this import is downloaded
	 * 
	 * @return boolean true if downloaded, false otherwise
	 */
	public function isDownloaded() {
		return is_file($this->file);
	}

	////////////////////////////////////////////////////////////

	/**
	 * Get the URL to import
	 * 
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Set the URL of this import
	 * 
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	////////////////////////////////////////////////////////////

	/**
	 * Get the phpQuery object
	 * 
	 * @return phpQueryObject
	 */
	public function getPhpQuery() {
		return $this->phpQuery;
	}

	/**
	 * Set the phpQuery object
	 * 
	 * @param phpQuery $phpQuery
	 */
	public function setPhpQuery(phpQueryObject $phpQuery) {
		$this->phpQuery = $phpQuery;
	}

	////////////////////////////////////////////////////////////

	/**
	 * Get the date of this import
	 * 
	 * @return DateTime
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * Set the import date
	 * 
	 * @param DateTime $date
	 */
	public function setDate(DateTime $date) {
		$this->date = $date;
	}

	////////////////////////////////////////////////////////////

	/**
	 * Set the specified post data
	 * 
	 * @param string $key
	 * @param mixed $value
	 */
	public function setPostData($key, $value) {
		$this->post[$key] = $value;
	}

	/**
	 * Get the post date
	 * 
	 * @param string $key
	 */
	public function getPostData($key) {
		$result = null;

		if(isset($this->post[$key])) {
			$result = $this->post[$key];
		}

		return $result;
	}

	public function getPostId() {
		return $this->getPostData('ID');
	}

	public function setPostId($id) {
		$this->setPostData('ID', $id);
	}

	/**
	 * Get the post type to import
	 * 
	 * @return string
	 */
	public function getPostType() {
		return $this->getPostData('post_type');
	}

	////////////////////////////////////////////////////////////

	/**
	 * Get the post meta
	 * 
	 * @param string $key
	 */
	public function getPostMeta($key) {
		$result = null;

		if(isset($this->meta[$key])) {
			$result = $this->meta[$key];
		}

		return $result;
	}
	
	/**
	 * Set the specified meta data
	 * 
	 * @param string $key
	 * @param mixed $value
	 */
	public function setPostMeta($key, $value) {
		$this->meta[$key] = $value;
	}

	public function getMeta($key) {
		$meta = null;

		if(isset($this->meta[$key])) {
			$meta = $this->meta[$key];
		}

		return $meta;
	}

	////////////////////////////////////////////////////////////

	/**
	 * Add the specified term
	 * 
	 * @param string $taxonomy
	 * @param mixed $term
	 */
	public function addTerm(TermInfo $term) {
		if ( ! isset( $this->taxonomies[$term->taxonomy] ) ) {
			$this->taxonomies[$term->taxonomy] = array();
		}

		$this->taxonomies[$term->taxonomy][] = $term;
	}

	////////////////////////////////////////////////////////////

	/**
	 * Add the specified media to this import info
	 * 
	 * @param self $media
	 */
	public function addMedia(self $media) {
		$url = $media->getUrl();

		$this->media[$url] = $media;
	}

	public function setThumbnail(self $media) {
		$this->thumbnail = $media;
	}

	public function isMediaDownloaded() {
		$downloaded = true;

		foreach($this->media as $media) {
			$downloaded &= $media->isDownloaded();
		}

		return $downloaded;
	}
	
	public function doesHaveDefinedThumbnail() {
		$this->definedThumbnail = true;
	}
	
	public function doesntHaveDefinedThumbnail() {
		$this->definedThumbnail = false;
	}
	
	public function hasDefinedThumbnail() {
		return (bool) $this->definedThumbnail;
	}

	////////////////////////////////////////////////////////////

	/**
	 * Add the specified comment to this import info
	 * 
	 * @param $comment
	 */
	public function addComment($comment) {
		$this->comments[] = $comment;
	}

	////////////////////////////////////////////////////////////

	/**
	 * Log the specified message
	 * 
	 * @param string $message
	 */
	public function log($message) {
		echo '<em>', date('H:i:s'), '</em>' , ' - ', $message, '<br />';
	}

	public function logNext() {
		echo '<br />';
	}
}
