<?php

class Importer {
	public $start;

	public $end;

	public function __construct() {
		
	}

	public function start(ImportInfo $import) {
		if($this->start != null) {
			$this->start->process($import);
		}
	}

	public function next(ImportAction $action) {
		if($this->end != null) {
			$this->end->setNext($action);
		} else {
			$this->start = $action;
		}

		$this->end = $action;
	}
}
