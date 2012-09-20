<?php

class FindPostMeta extends ImportAction {
	public function __construct($selector) {
		$this->selector = $selector;
	}

	public function process(ImportInfo $import) {
		$phpQuery = $import->getPhpQuery();

		$import->log('Searching for elements with post meta &hellip;');
		$context = $phpQuery->find($this->selector);
		$import->log(sprintf('Found "<strong>%d</strong>" elements with post meta', $context->length));
		
		$import->log('Searching for table rows in context &hellip;');
		$rows = $context->find('.standaardtabel tr');
		$import->log(sprintf('Found "<strong>%d</strong>" rows in context', $rows->length));
			
		foreach($rows as $row) {
			$row = pq($row);

			$keyElement = $row->find('td:eq(0)');
			$key = $keyElement->html();
			$key = trim($key);
		
			$valueElement = $row->find('td:eq(1)');
			$value = $valueElement->html();
			$value = trim($value);

			$import->log(sprintf('Found meta data: %s = %s', $key, $value));
	
			$import->setPostMeta($key, $value);
		}
	
		$context->replaceWith('[meta]');
		$import->log('Replaced context element with WordPress [meta] shortcode');

		$this->next($import);
	}
}
