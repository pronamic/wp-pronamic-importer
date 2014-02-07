<?php

class UpdateFileLink extends ImportAction {
	public function process(ImportInfo $import) {
        
		foreach($import->media as $media) {
            $anchors = $import->contentElement->find( 'a[href*="' . $media->getUrl() . '"]' );
            foreach ( $anchors as $anchor ) {
                $anchor = pq( $anchor );
                
                $anchor->attr( 'href', wp_get_attachment_url( $media->getPostId() ) );
            }
		}
		
		$this->next($import);
	}
}
