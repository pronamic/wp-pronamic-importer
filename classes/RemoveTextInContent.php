<?php

class RemoveTextInContent extends ImportAction {
	public function __construct($text) {
		$this->text = $text;
	}

	public function process(ImportInfo $import) {
		$import->log(sprintf('Removing text in content: "<strong>%s</strong>" &hellip; ', htmlspecialchars($this->text)));

		$content = $import->getPostData('post_content');

		$content = str_replace($this->text, '', $content);

		$import->setPostData('post_content', $content);	
		
		$import->log('Removed text in content');

		$this->next($import);
	}
}
