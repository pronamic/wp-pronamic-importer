<?php

class UpdateImageSource extends ImportAction {
	public function process(ImportInfo $import) {
		foreach($import->media as $media) {
			$phpQuery = $media->getPhpQuery();

			if(isset($media->imageElement)) {
				$result = wp_get_attachment_image_src($media->getPostId(), 'full');

				$media->imageElement->attr('src', $result[0]);
			}
		}
		
		$this->next($import);
	}
}
