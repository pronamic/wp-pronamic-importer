<?php

class InsertPost extends ImportAction {
	public function process(ImportInfo $import) {
		$import->log(sprintf('Inserting WordPress post with <strong>%d</strong> fields &hellip;', count($import->post)));

		$result = wp_insert_post($import->post, true);

		if(is_wp_error($result)) {
			$import->log('Failed to insert post');
		} else {
			$import->log(sprintf('Inserted WordPress post with ID %s', $result));
	
			$import->setPostId($result);
	
			$this->next($import);
		}
	}
}
