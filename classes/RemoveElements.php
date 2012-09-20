<?php

class RemoveElements extends ImportAction {
	public function __construct($selector) {
		$this->selector = $selector;
	}

	public function process(ImportInfo $import) {
		$phpQuery = $import->getPhpQuery();

		$import->log(sprintf('Searching for element: <strong>%s</strong>', $this->selector));
		$elements = $phpQuery->find($this->selector);
		$import->log(sprintf('Removing <strong>%d</strong> elements', $elements->length));
		$elements->remove();
	
		$this->next($import);
	}
}
