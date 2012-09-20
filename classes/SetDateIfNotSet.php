<?php

use Pronamic\Bouwmedia\Zibbsearch;

class SetDateIfNotSet extends ImportAction {
	public function process(ImportInfo $import) {
		$import->log(sprintf('Setting the current date if not set'));

		$date = $import->getDate();

		if($date == null) {
			$date = new DateTime();

			$import->log(sprintf('Date not set, set the date to now: "<strong>%s</strong>"', $date->format(DATE_W3C)));

			$import->setDate($date);
		} else {
			$import->log(sprintf('Date already set: "<strong>%s</strong>"', $date->format(DATE_W3C)));
		}

		$this->next($import);
	}
}
