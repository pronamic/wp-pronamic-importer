<?php

class FindPostThumbnail extends ImportAction {
	public function __construct($selector) {
		$this->selector = $selector;
	}

	public function process(ImportInfo $import) {
		$phpQuery = $import->getPhpQuery();

		$import->log(sprintf('Searching for post thumbnail in element: "%s" &hellip;', $this->selector));

		$context = $phpQuery->find($this->selector);

		$import->log(sprintf('Found "<strong>%d</strong>" post thumbnails elements', $context->length));

		$image = $context->find('img:first');

		if($image->length > 0) {
			$url = $image->attr('src');
			$url = 'http://www.architectuur.nl' . $url;

			$import->log(sprintf('Found image: "<strong>%s</strong>"', $url));

			$alt = $image->attr('alt');

			$photo = new ImportInfo($url);
			$photo->setPostData('post_excerpt', $alt);
			$photo->setPostMeta('_import_url', $url);

			$import->addMedia($photo);
			$import->setThumbnail($photo);

			$context->remove();
		}

		$this->next($import);
	}
}
