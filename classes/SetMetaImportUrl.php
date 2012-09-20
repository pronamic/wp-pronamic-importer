<?php

use Pronamic\Bouwmedia\Post;

class SetMetaImportUrl extends ImportAction {
	private $key;

	public function __construct($key) {
		$this->key = $key;
	}

	public function process(ImportInfo $import) {		
		$key = $this->key;
		$value = $import->getUrl();

		$import->log(sprintf('Set import URL in post meta: <strong>%s</strong> = <strong>%s</strong>', $key, $value));

		$import->setPostMeta($key, $value);

		$this->next($import);
	}
}
