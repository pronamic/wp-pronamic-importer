<?php

class SetPostContent extends ImportAction {
	/**
	 * Process this action
	 * 
	 * @see ImportAction::process()
	 */
	public function process(ImportInfo $import) {
		if(isset($import->contentElement)) {
			$content = $import->contentElement->html();

			$import->log(sprintf('Set the post content'));

			$import->setPostData('post_content', $content);  
		} else {
			$import->log(sprintf('No post content element found'));
		}

		$this->next($import);
	}
}
