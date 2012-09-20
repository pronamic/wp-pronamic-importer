<?php

class FindMetaDescription extends ImportAction {
	public function process(ImportInfo $import) {
		$phpQuery = $import->getPhpQuery();

		$selector = 'meta[name="description"]';

		$import->log(sprintf('Searching for meta description in element: "%s" &hellip;', $selector));

		$context = $phpQuery->find($selector);

		$description = $context->attr('content');

		$context->remove();

		$import->log(sprintf('Found meta description: "%s"', substr($description, 0, 50)));

		$import->setPostData('post_excerpt', $description);

		$this->next($import);
	}
}
