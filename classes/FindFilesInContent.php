<?php

class FindFilesInContent extends ImportAction {
	public function process(ImportInfo $import) {
		$import->log(sprintf('Searching for files in post content &hellip;'));

		$anchors = $import->contentElement->find('a[href*=""]');

		$import->log(sprintf('Found <strong>%d</strong> links in post content &hellip;', $anchors->length));
		
		$extensions = array(
			'.pdf',
			'.doc',
			'.docx',
			'.txt',
			'.xls',
			'.xlsx',
			'.pps'
		);
		
		foreach($anchors as $anchor) {
			$anchor = pq($anchor);

			$url = $anchor->attr('href');
			$lower_url = strtolower($url);
			foreach($extensions as $ext) {
				if( false !== strstr($lower_url, $ext)) {
					$import->log(sprintf('Found file: "<strong>%s</strong>"', $url));

					$media = new ImportInfo($url);
					$media->setPostData('post_title', $anchor->text());
					$media->setPostData('post_content', $anchor->attr('title'));
					$media->anchorElement = $anchor;

					$import->addMedia($media);
				}
			}
		}
		
		$this->next($import);
	}
}