<?php

/**
 * Title: Importer
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Importer_Importer extends WP_Importer {
	/**
	 * Dispatch
	 */
	public function dispatch() {
		include Pronamic_Importer_Plugin::$dirname . '/admin/importer.php';
	}

	//////////////////////////////////////////////////

	/**
	 * Get default importer
	 * 
	 * @param PDO $pdo
	 */
	public static function get_default_importer( $pdo, $table, $field_id ) {
		$importer = new Importer();
	
		$importer->next( new ExecuteQuery( $pdo, sprintf( 'UPDATE %s SET wordpress_import_attempts = wordpress_import_attempts + 1 WHERE %s = :import_id;', $table, $field_id ) ) );
	
		$importer->next( new CreatePhpQueryFromPostContent() );
	
		$importer->next( new SetPostStatus( 'publish' ) );
	
		$importer->next( new SetMetaImportUrl( '_import_url' ) );
	
		$importer->next( new SetDateIfNotSet() );
		$importer->next( new SetPostDate() );
	
		$importer->next( new ConvertRelativeUrlToAbsolute( 'img', 'src' ) );
		$importer->next( new ConvertRelativeUrlToAbsolute( 'a', 'href' ) );

		$importer->next( new Pronamic_Importer_Actions_StripSlashesPostData( 'post_title' ) );
		$importer->next( new Pronamic_Importer_Actions_StripSlashesPostData( 'post_content' ) );
		
		$importer->next( new SetPostContent() );
		
		$importer->next( new RemoveElements( 'p:empty' ) );
	
		$importer->next( new FindPostThumbnail( 'img:first' ) );
		
		$importer->next( new FindImagesInContent() );
		$importer->next( new FindFilesInContent() );
		
		$importer->next( new SetPostThumbnailIfNotSet() );
	
		$importer->next( new DownloadMedia() );
		
		$importer->next( new InsertPost() );
		$importer->next( new InsertAttachments() );
			
		$importer->next( new UpdateImageSource() );
		$importer->next( new UpdateFileLink() );
	
		$importer->next( new SetPostContent() );
		
		$importer->next( new TidyContent( array( 'css-prefix' => 'pronamic-import' ) ) );
	
		$importer->next( new TrimContent() );
	// iframe importer
		$importer->next( new UpdatePost() );
		
		$importer->next( new AddPostMeta() );
		$importer->next( new SetPostTerms() );
		
		$importer->next( new InsertComments() );
	
		$importer->next( new DeleteTemporaryFiles() );
		
		$importer->next( new VarDumpImport() );
	
		$importer->next( new ExecuteQuery( $pdo, sprintf( 'UPDATE %s SET wordpress_imported = TRUE WHERE %s = :import_id;', $table, $field_id ) ) );
	
		$importer->next( new Done() );
	
		return $importer;
	}
}
