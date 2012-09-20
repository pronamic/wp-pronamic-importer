<?php

class SetPostDate extends ImportAction {
	/**
	 * Process this action
	 * 
	 * @see ImportAction::process()
	 */
	public function process(ImportInfo $import) {
		$date = $import->getDate();

		$import->log(sprintf('Set the post date: "<strong>%s</strong>"', $date->format(DATE_W3C)));

		$import->setPostData('post_date', $date->format('Y-m-d H:i:s')); 
		$import->setPostData('post_date_gmt', get_gmt_from_date($date->format('Y-m-d H:i:s'))); 

		$this->next($import);
	}
}
