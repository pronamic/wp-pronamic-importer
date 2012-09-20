<?php

class CreatePhpQuery extends ImportAction {
	public function process(ImportInfo $import) {
		$import->log(sprintf('Creating phpQuery object &hellip;'));

		$phpQuery = phpQuery::newDocumentFileHTML($import->file);

		$import->setPhpQuery($phpQuery);

		$import->log(sprintf('Created phpQuery object'));

		$this->next($import);
	}
}
