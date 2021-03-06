<?php

class FindPostThumbnail extends ImportAction {
	public function __construct($selector) {
		$this->selector = $selector;
	}

	public function process(ImportInfo $import) {
		$phpQuery = $import->getPhpQuery();

		$import->log(sprintf('Searching for post thumbnail in element: "%s" &hellip;', $this->selector));

		$image = $phpQuery->find($this->selector);

		$import->log(sprintf('Found "<strong>%d</strong>" post thumbnails elements', $image->length));

		if($image->length > 0 && ! $import->hasDefinedThumbnail() && $import->shouldGuessThumbnail() ) {
			$url = $image->attr('src');
			
			$import->log(sprintf('Found image: "<strong>%s</strong>"', $url));

			$alt = $image->attr('alt');

			$photo = new ImportInfo($url);
			$photo->setPostData('post_excerpt', $alt);
			$photo->setPostMeta('_import_url', $url);
			
			$import->addMedia($photo);
			$import->setThumbnail($photo);
		} else {
			if ( $import->hasDefinedThumbnail() )
				$import->log( 'This Import has a specific thumbnail' );
			
			if ( ! $import->shouldGuessThumbnail() )
				$import->log( 'This Import has been specified to not guess the thumbnail' );
		}

		$this->next($import);
	}
}
