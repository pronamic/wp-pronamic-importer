<?php

abstract class ImportAction {
	public $next;

	public abstract function process(ImportInfo $import);

	public function setNext(self $action) {
		$this->next = $action;
	}

	public function next(ImportInfo $import) {
		if($this->next != null) {
			$import->logNext();

			return $this->next->process($import);
		}
	}
}
