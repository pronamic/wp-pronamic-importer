<?php

class DeleteTemporaryFiles extends ImportAction {
	private $temporaryDir;

	public function __construct() {
		$this->temporaryDir = sys_get_temp_dir();
	}

	private function deleteFile(ImportInfo $import) {
		if(String::startsWith($import->file, $this->temporaryDir)) {
			$import->log(sprintf('Deleting file: "<strong>%s</strong>" &hellip;', $import->file));

			$deleted = unlink($import->file);
			if($deleted) {
				$import->log(sprintf('Deleted file: "<strong>%s</strong>"', $import->file));
			} else {
				$import->log(sprintf('Failed deleting file: "<strong>%s</strong>"', $import->file));
			}
		} else {
			$import->log(sprintf('File is not an temporary file: "<strong>%s</strong>"', $import->file));
		}
	}

	public function process(ImportInfo $import) {
		$this->deleteFile($import);

		foreach($import->media as $media) {
			$this->deleteFile($media);
		}
		
		$this->next($import);
	}
}
