<?php

class AddTerm extends ImportAction {
	public function __construct(TermInfo $term) {
		$this->term = $term;
	}

	public function process(ImportInfo $import) {
		$import->log(sprintf(
			'Add post to term "<strong>%s</strong>" of the taxonomy "<strong>%s</strong>"', 
			$this->term->taxonomy , 
			$this->term->name
		));

		$import->addTerm($this->term);

		$this->next($import);
	}
}
