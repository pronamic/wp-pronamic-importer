<?php

class RemoveUrlFragment extends ImportAction {
	public function process(ImportInfo $import) {
		$import->log(sprintf('Remvoing fragment from URL: "<strong>%s</strong>"', $import->getUrl()));

		$url = $import->getUrl();
		$url = new Url($url);
		$url->setFragment(null);

		$import->setUrl($url->__toString());

		$import->log(sprintf('Removed fragment from URL: "<strong>%s</strong>"', $url));

		$this->next($import);
	}
}
