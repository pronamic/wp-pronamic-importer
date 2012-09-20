<?php

class UpdateFileLink extends ImportAction {
	public function process(ImportInfo $import) {
		foreach($import->media as $media) {
			$phpQuery = $media->getPhpQuery();

			if(isset($media->anchorElement)) {
				$result = wp_get_attachment_url($media->getPostId());

				$media->anchorElement->attr('href', $result);
			}
		}
		
		$this->next($import);
	}
}
