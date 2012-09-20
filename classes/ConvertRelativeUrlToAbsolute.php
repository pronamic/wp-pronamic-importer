<?php

class ConvertRelativeUrlToAbsolute extends ImportAction {
	public function __construct($selector, $attribute) {
		$this->selector = $selector;
		$this->attribute = $attribute;
	}

	public function process(ImportInfo $import) {
		$phpQuery = $import->getPhpQuery();

		$import->log(sprintf('Searching for elements: <strong>%s</strong>', $this->selector));
		
		$elements = $phpQuery->find($this->selector);

		foreach($elements as $element) {
			$pqElement = pq($element);

			$import->log(sprintf('Found element: <strong>%s</strong>', $element->tagName));

			$urlOriginal = $pqElement->attr($this->attribute);

			$import->log(sprintf('Found URL <strong>%s</strong> in attribute <strong>%s</strong>', $urlOriginal, $this->attribute));

			$urlNew = url_to_absolute($import->getUrl(), $urlOriginal);
			
			if($urlNew !== false) {
				$import->log(sprintf('Modified URL from <strong>%s</strong> to <strong>%s</strong>', $urlOriginal, $urlNew));
			
				$pqElement->attr($this->attribute, $urlNew);
			} else {
				$import->log(sprintf('Could not covert URL <strong>%s</strong> to absolute URL', $urlOriginal));
			}
		}
	
		$this->next($import);
	}
}
