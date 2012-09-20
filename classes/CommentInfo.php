<?php

class CommentInfo {
	public $data;

	public $meta;

	public function __construct() {
		$this->data = array();
		$this->meta = array();
	}

	public function setCommentData($key, $value) {
		$this->data[$key] = $value;
	}

	public function setCommentMeta($key, $value) {
		$this->meta[$key] = $value;
	}
}
