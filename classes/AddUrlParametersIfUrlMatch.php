<?php

class AddUrlParametersIfUrlMatch extends ImportAction {
	public function __construct($pattern, $parameters) {
		$this->pattern = $pattern;
		$this->parameters = $parameters;
	}

	public function process(ImportInfo $import) {
		$import->log(sprintf('Add URL paremeters if URL match'));

		$import->log(sprintf('Checking if URL matches the pattern: "<strong>%s</strong>" &hellip;', $this->pattern));

		$match = preg_match($this->pattern, $import->getUrl());
		if($match) {
			$import->log(sprintf('URL matches the pattern'));

			$url = $import->getUrl();

			foreach($this->parameters as $key => $value) {
				$url = add_query_arg($key, $value, $url);
			}

			$import->setUrl($url);
			
			$import->log(sprintf('Added parameters to URL: "<strong>%s</strong>"', $url));
		} else {
			$import->log(sprintf('URL did not matched the pattern'));
		}

		$this->next($import);
	}
}
