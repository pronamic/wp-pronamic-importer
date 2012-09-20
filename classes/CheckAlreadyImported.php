<?php

class CheckAlreadyImported extends ImportAction {
	public function process(ImportInfo $import) {
		$query = new WP_Query();
		$query->query(array(
			'post_type' => 'any' ,
			'meta_query' => array(
				array(
					'key' => '_import_url' , 
					'value' => $import->getUrl() 
				)
			)
		));

		$imported = $query->post_count > 0;

		unset($query);

		if($imported) {
			$import->log(sprintf('The URL "%s" is already imported', $import->getUrl()));
		} else {
			$import->log(sprintf('The URL "%s" is not imported', $import->getUrl()));

			$this->next($import);
		}
	}
}
