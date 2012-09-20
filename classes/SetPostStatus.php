<?php

class SetPostStatus extends ImportAction {
	/**
	 * The status
	 * 
	 * @var string
	 */
	private $status;

	////////////////////////////////////////////////////////////

	/**
	 * Constructs and initializes an set post status action
	 * 
	 * @param string $status
	 */
	public function __construct($status) {
		$this->status = $status;
	}

	////////////////////////////////////////////////////////////

	/**
	 * Process this action
	 * 
	 * @see ImportAction::process()
	 */
	public function process(ImportInfo $import) {
		$import->log(sprintf('Setting post status: "<strong>%s</strong>"', $this->status));

		$import->setPostData('post_status', $this->status);

		$this->next($import);
	}
}
