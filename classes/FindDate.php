<?php

class FindDate extends ImportAction {
	private $selector;

	public function __construct($selector) {
		$this->selector = $selector;
	}

	public function process(ImportInfo $import) {
		$import->log(sprintf('Searching for date in the content if not set'));

		$phpQuery = $import->getPhpQuery();
	
		$import->log(sprintf('Searching for date in element: "%s" &hellip;', $this->selector));
	
		$context = $phpQuery->find( $this->selector);

		$date = $context->text();
		$date = trim($date);
		$date = trim($date, '()');
		$date = DateTime::createFromFormat('!d-m-Y', $date);
	
		$context->remove();

		if($import->getDate() == null) {
			if($date !== false) {
				$import->setDate($date);

				$import->log(sprintf('Found date: "<strong>%s</strong>"', $date->format(DATE_W3C)));
			}
		} else {
			$import->log(sprintf('Date already set: "<strong>%s</strong>"', $import->getDate()->format(DATE_W3C)));
		}

		$this->next($import);
	}
}
