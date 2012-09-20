<?php

class TermInfo {
	public $taxonomy;

	public $id;

	public $name;

	public $slug;
	
	public $parent;

	public $children;
	
	public function __construct($taxonomy, $name, self $child = null) {
		$this->taxonomy = $taxonomy;
		$this->name = $name;

		$this->children = array();

		if($child != null) {
			$this->addChild($child);
		}
	}	

	public function addChild(TermInfo $term) {
		$term->parent = $this;

		$this->children[] = $term;
	}
}