<?php

class FindDateBetweenStrings extends ImportAction {
	private $beforeString;
	
	private $afterString;

	public function __construct($beforeString, $afterString, $format) {
		$this->beforeString = $beforeString;
		$this->afterString = $afterString;
		$this->format = $format;
	}

	public function process(ImportInfo $import) {
		$import->log(sprintf(
			'Searching for date between strings: "<strong>%s</strong>" and "<strong>%s</strong>"' ,  
			htmlspecialchars($this->beforeString) ,  
			htmlspecialchars($this->afterString) 
		));

		$phpQuery = $import->getPhpQuery();
	
		$text = String::between($phpQuery->html(), $this->beforeString, $this->afterString);

		$import->log(sprintf('Found text: <strong>%s</strong>', $text));

		$import->log(sprintf('Create date time object from text in format: <strong>%s</strong>', $this->format));

		$date = DateTime::createFromFormat($this->format, $text);
		if($date !== false) {
			$import->log(sprintf('Succesfully created date time object'));

			$import->setDate($date);

			$import->log(sprintf('Found date: "<strong>%s</strong>"', $date->format(DATE_W3C)));
		} else {
			$import->log(sprintf('Failed creating date time object'));
		}

		$this->next($import);
	}
}
