<?php

/**
 * Title: Strip slashes post data import action
 * Description: This action strip slashes from the specified post data
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */

class Pronamic_Importer_Actions_StripSlashesPostData extends ImportAction {
	/**
	 * Constructs and initializes an strip slashes post data import action
	 * 
	 * @param string $key
	 */
	public function __construct( $key ) {
		$this->key = $key;
	}

	/**
	 * Process
	 * 
	 * @param ImportInfo $import
	 * @see ImportAction::process()
	 */
	public function process( ImportInfo $import ) {
		$key   = $this->key;
		$value = $import->getPostData( $key );

		$import->log( sprintf( 'Strip slashes from "%s": <pre>%s</pre>', $key, htmlspecialchars( $value ) ) );

		$value = stripslashes( $value );
		
		$import->log( sprintf( 'Strip slashes result  "%s": <pre>%s</pre>', $key, htmlspecialchars( $value ) ) );

		$import->setPostData( $key, $value );

		$this->next( $import );
	}
}
