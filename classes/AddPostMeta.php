<?php

class AddPostMeta extends ImportAction {
	public function process(ImportInfo $import) {
		$postId = $import->getPostId();

		$import->log(sprintf('Adding meta data to post with ID: %s', $postId));

		foreach($import->meta as $key => $value) {
			$import->log(sprintf('Adding meta data: %s = %s', htmlspecialchars($key), htmlspecialchars($value)));

			$result = add_post_meta($postId, $key, $value);

			if($result !== false) {
				$import->log(sprintf('Succesfully saved meta data: %s = %s', htmlspecialchars($key), htmlspecialchars($value)));
			} else {
				$import->log(sprintf('Somehting went wrong while saving meta data: %s = %s', htmlspecialchars($key), htmlspecialchars($value)));
			}
		}

		$import->log(sprintf('Done with adding %s fields with meta data', count($import->meta)));

		$this->next($import);
	}
}
