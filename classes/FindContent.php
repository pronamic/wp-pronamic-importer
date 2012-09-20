<?php

class FindContent extends ImportAction {
	public function __construct($selector) {
		$this->selector = $selector;
	}

	public function process(ImportInfo $import) {
		$phpQuery = $import->getPhpQuery();

		$import->log(sprintf('Searching for content in element: "%s" &hellip;', $this->selector));

		$import->contentElement = $phpQuery->find($this->selector);

		$import->log(sprintf('Found "<strong>%d</strong>" content elements', $import->contentElement->length));
		
		$this->next($import);
	}
}
