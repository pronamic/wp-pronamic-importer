<?php

class TrimContent extends ImportAction {
	public function process(ImportInfo $import) {
		$import->log('Trim content &hellip;');

		$content = $import->getPostData('post_content');

		$content = trim($content);

		$import->setPostData('post_content', $content);	
		
		$import->log('Trimmed content');

		$this->next($import);
	}
}
