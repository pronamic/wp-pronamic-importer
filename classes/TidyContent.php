<?php

class TidyContent extends ImportAction {
	private $config;

	public function __construct($config = array()) {
		// @see http://tidy.sourceforge.net/docs/quickref.html
		$this->config = wp_parse_args($config,  array(
			'clean' => true , 
			'indent' => true , 
			'output-xhtml' => true ,
			'show-body-only' => true ,  
			'wrap' => 0 
		));
	}

	public function process(ImportInfo $import) {
		$content = $import->getPostData('post_content');

		$import->log(sprintf('Cleaning and repair content (content length: %d) &hellip;', strlen($content)));
		
		$tidy = tidy_parse_string($content, $this->config, 'UTF8');
		
		$tidy->cleanRepair();
		
		$content = (string) $tidy;

		$import->log(sprintf('Cleaned and repaired content (content length: %d)', strlen($content)));

		$import->setPostData('post_content', $content);	

		$this->next($import);
	}
}
