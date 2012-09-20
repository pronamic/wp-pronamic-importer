<?php

class FindMetaKeywords extends ImportAction {
	public function process(ImportInfo $import) {
		$phpQuery = $import->getPhpQuery();

		$selector = 'meta[name="keywords"]';

		$import->log(sprintf('Searching for keywords in element: "%s" &hellip;', $selector));

		$context = $phpQuery->find($selector);

		$keywords = $context->attr('content');

		$context->remove();

		$import->log(sprintf('Found meta keywords: "%s"', substr($keywords, 0, 50)));

		$import->setPostMeta('_yoast_wpseo_metakeywords', $keywords);

		$this->next($import);
	}
}
