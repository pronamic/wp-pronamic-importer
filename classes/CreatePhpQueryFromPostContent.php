<?php

class CreatePhpQueryFromPostContent extends ImportAction {
	public function process(ImportInfo $import) {
		$import->log(sprintf('Creating phpQuery object from post content &hellip;'));

		$phpQuery = phpQuery::newDocument($import->getPostData('post_content'));

		$import->setPhpQuery($phpQuery);
		$import->contentElement = $phpQuery;

		$import->log(sprintf('Created phpQuery object'));

		$this->next($import);
	}
}
