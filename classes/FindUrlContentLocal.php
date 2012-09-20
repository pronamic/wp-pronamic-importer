<?php

class FindUrlContentLocal extends ImportAction {
	private $path;

	public function __construct($path) {
		$this->path = $path;
	}

	public function process(ImportInfo $import) {
		$import->log(sprintf('Check if content is already available &hellip;'));

		if(is_file($import->file)) {
			$import->log(sprintf('Content available'));
		} else {
			$import->log(sprintf('Content not available'));

			$url = $import->getUrl();

			$path = substr($url, 7);

			$file = $this->path . DIRECTORY_SEPARATOR . $path;

			if(is_readable($file)) {
				$import->file = $file;
			}
		}
			
		$this->next($import);
	}
}
