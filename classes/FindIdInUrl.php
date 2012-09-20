<?php

class FindIdInUrl extends ImportAction {
	public function __construct($pattern) {
		$this->pattern = $pattern;
	}

	public function process(ImportInfo $import) {
		$url = $import->getUrl();

		$import->log(sprintf('Searching for ID in URL: "<strong>%s</strong>" &hellip;', $url));

		preg_match($this->pattern, $url, $matches);

		if(isset($matches['id'])) {
			$id = $matches['id'];

			$import->log(sprintf('Found ID: "<strong>%s</strong>"', $id));

			$import->setPostMeta('_import_id', $id);
		} else {
			$import->log(sprintf('Found no ID'));
		}

		$this->next($import);
	}
}