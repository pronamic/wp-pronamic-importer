<?php

class FindTitle extends ImportAction {
	private $selector;

	public function __construct($selector) {
		$this->selector = $selector;
	}

	public function process(ImportInfo $import) {
		$phpQuery = $import->getPhpQuery();

		$import->log(sprintf('Searching for title in element: "<strong>%s</strong>" &hellip;', $this->selector));

		$context = $phpQuery->find($this->selector);

		$title = $context->text();
		$title = trim($title);

		$context->remove();

		$import->log(sprintf('Found title: "<strong>%s</strong>"', $title));

		$import->setPostData('post_title', $title);

		$this->next($import);
	}
}
