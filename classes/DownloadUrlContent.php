<?php

class DownloadUrlContent extends ImportAction {
	public function process(ImportInfo $import) {
		$import->log(sprintf('Check if content is already available &hellip;'));

		if(is_readable($import->file)) {
			$import->log(sprintf('Content available'));
		} else {
			$import->log(sprintf('Content not available'));

			$url = $import->getUrl();
	
			$import->log(sprintf('Downloading "<strong>%s</strong>" &hellip;', $url));
	
			$result = download_url($url);
	
			if(is_wp_error($result)) {
				$import->log(sprintf('Failed downloading: "<strong>%s</strong>"', $url));
			} else {
				$import->log(sprintf('Succesfully downloaded: "<strong>%s</strong>"', $result));
	
				$import->file = $result;
			}
		}

		$this->next($import);
	}
}
