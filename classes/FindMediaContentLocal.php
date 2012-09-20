<?php

class FindMediaContentLocal extends ImportAction {
	private $path;

	public function __construct($path) {
		$this->path = $path;
	}

	public function process(ImportInfo $import) {
		$import->log(sprintf('Check if media is available local &hellip;'));

		foreach($import->media as $media) {
			if(is_readable($media->file)) {
				$import->log(sprintf('Media file already available'));
			} else {
				$url = $media->getUrl();
				
				$path = parse_url($url, PHP_URL_PATH);

				$file = $this->path . DIRECTORY_SEPARATOR . $path;
						
				if(is_readable($file)) {
					$import->log(sprintf('Local media file <strong>is</strong> available: <strong>%s</strong>', $file));

					$media->file = $file;
				} else {
					$import->log(sprintf('Local media file <strong>is not</strong> available: <strong>%s</strong>', $file));
				}
			}
		}
			
		$this->next($import);
	}
}
