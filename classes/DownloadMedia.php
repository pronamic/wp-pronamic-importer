<?php

class DownloadMedia extends ImportAction {
	public function process(ImportInfo $import) {
		foreach($import->media as $media) {
			$url = $media->getUrl();
			$url = str_replace(' ', '%20', $url);

			$import->log(sprintf('Downloading "%s" &hellip;', $url));

			if(is_readable($media->file)) {
				$import->log(sprintf('Media file already available "%s" &hellip;', $url));
			} else {
				$result = download_url($url);

				if(is_wp_error($result)) {
					$import->log(sprintf('Download failed: <strong>%s</strong>', $url));
				} else {
					$import->log(sprintf('Download succeeded: <strong>%s</strong>', $result));
				
					$media->file = $result;
				}
			}
		}

		if($import->isMediaDownloaded()) {
			$this->next($import);
		} else {
			$import->log('Failed downloading all media');
			$this->next($import);
		}
	}
}
