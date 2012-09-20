<?php

class Done extends ImportAction {
	public function process(ImportInfo $import) {
		$import->log(sprintf('Done import: %s', $import->getUrl()));

		$id = $import->getPostId();

		$import->log(sprintf('Published post on permalink <a href="%s">%s</a>', get_permalink($id), get_permalink($id)));

		$this->next($import);
	}
}
