<?php

use Pronamic\Bouwmedia\Zibbsearch;

class SetPostThumbnailIfNotSet extends ImportAction {
	public function process(ImportInfo $import) {
		if ( $import->shouldGuessThumbnail() ) {
			$import->log(sprintf('Setting the post thumbnail if not set'));

		
			$import->log(sprintf('Checking if the post thumbnails is not set &hellip;'));

			if(empty($import->thumbnail)) {
				$import->log(sprintf('Post thumbnail is not set'));

				$thumbnail = current($import->media);

				if($thumbnail !== false) {
					$import->setThumbnail($thumbnail);
				}
			} else {
				$import->log(sprintf('Post thumbnail is set'));
			}

			
		} else {
			$import->log( 'This Import has been specified to not guess the thumbnail' );
		}
		
		$this->next($import);
	}
}
