<?php

class FindImagesInContent extends ImportAction {
	public function __construct($selector = 'img') {
		$this->selector = $selector;
	}

	public function process(ImportInfo $import) {
		$import->log(sprintf('Searching for images in post content &hellip;'));

		$images = $import->contentElement->find($this->selector);

		$import->log(sprintf('Found <strong>%d</strong> images in post content &hellip;', $images->length));
		
		foreach($images as $image) {
			$image = pq($image);

			$url = $image->attr('src');

			$import->log(sprintf('Found image: "<strong>%s</strong>"', $url));

			$media = new ImportInfo($url);
			$media->setPostData('post_content', $image->attr('alt'));
			$media->setPostMeta('_import_url', $url);
			$media->imageElement = $image;

			$import->addMedia($media);
		}
		
		$this->next($import);
	}
}