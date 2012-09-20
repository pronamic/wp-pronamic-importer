<?php

class ExecuteQuery extends ImportAction {
	public function __construct($pdo, $query) {
		$this->pdo = $pdo;
		$this->query = $query;
	}

	public function process(ImportInfo $import) {
		$statement = $this->pdo->prepare($this->query);

		$import->log(sprintf('Executing query: "%s" &hellip;', $this->query));

		$result = $statement->execute(array(':import_id' => $import->getPostMeta('_import_id')));

		if($result) {
			$this->next($import);
		} else {
			$import->log(sprintf('Error executing query "%s"', $this->query));
		}
	}
}
