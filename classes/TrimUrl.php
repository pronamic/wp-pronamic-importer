<?php

class TrimUrl extends ImportAction {
	public function process(ImportInfo $import) {
		$import->log(sprintf('Trim URL'));

		$url = $import->getUrl();

		$url = trim($url);

		$import->setUrl($url);

		$this->next($import);
	}
}
