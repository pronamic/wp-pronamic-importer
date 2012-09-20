<?php

class VarDumpImport extends ImportAction {
	public function process(ImportInfo $import) {
		$import->log(sprintf('Var dump import'));

		$content = $import->getPostData('post_content');

		echo '<pre>';
		echo htmlspecialchars($content);
		echo '</pre>';

		$this->next($import);
	}
}
