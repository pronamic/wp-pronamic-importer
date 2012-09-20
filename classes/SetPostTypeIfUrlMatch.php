<?php

class SetPostTypeIfUrlMatch extends ImportAction {
	public function __construct($pattern, $postType) {
		$this->pattern = $pattern;
		$this->postType = $postType;
	} 

	public function process(ImportInfo $import) {
		$import->log(sprintf('Set post type "<strong>%s</strong>" if URL match: <strong>%s</strong> &hellip;', $this->postType, $this->pattern));

		$match = preg_match($this->pattern, $import->getUrl());
		if($match) {
			$import->log(sprintf('URL matches pattern, set post type: <strong>%s</strong>', $this->postType));

			$import->setPostData('post_type', $this->postType);
		} else {
			$import->log(sprintf('URL doest not match pattern'));
		}

		$this->next($import);
	}
}
