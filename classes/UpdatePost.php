<?php

class UpdatePost extends ImportAction {
	public function process(ImportInfo $import) {
		$import->log(sprintf('Updating WordPress post with <strong>%d</strong> fields &hellip', count($import->post)));

		$result = wp_update_post($import->post);

		if($result == 0) {
			$import->log('Failed to update post');
		} else {
			$import->log(sprintf('Updated WordPress post with ID %s', $result));
	
			$this->next($import);
		}
	}
}
