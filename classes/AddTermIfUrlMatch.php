<?php

class AddTermIfUrlMatch extends ImportAction {
	public function __construct($pattern, $taxonomy, $term) {
		$this->pattern = $pattern;
		$this->taxonomy = $taxonomy;
		$this->term = $term;
	}

	public function process(ImportInfo $import) {
		$import->log(sprintf(
			'Checking if the post is part of the "<strong>%s</strong>" term in the taxonomy "<strong>%s</strong>"', 
			$this->term , 
			$this->taxonomy
		));

		$match = preg_match($this->pattern, $import->getUrl());
		if($match) {
			$import->log(sprintf(
				'Post is part of the "<strong>%s</strong>" term in the taxonomy "<strong>%s</strong>"', 
				$this->term , 
				$this->taxonomy
			));

			$term = new TermInfo($this->taxonomy, $this->term);

			$import->addTerm($term);
		} else {
			$import->log(sprintf(
				'Post is not part of the "<strong>%s</strong>" term in the taxonomy "<strong>%s</strong>"', 
				$this->term , 
				$this->taxonomy
			));
		}

		$this->next($import);
	}
}
