<?php

class InsertComments extends ImportAction {
	public function process(ImportInfo $import) {
		$import->log(sprintf('Inserting WordPress comments &hellip;'));

		foreach($import->comments as $commentInfo) {
			$data = wp_parse_args(
				$commentInfo->data , 
				array(
					'comment_post_ID' => $import->getPostId() , 
				)
			);

			$result = wp_insert_comment($data);
		}
		
		$this->next($import);
	}
}
