<?php

class Pronamic_Importer_Data {
	private $pdo;

	public function __construct($pdo) {
		$this->pdo = $pdo;		
	}

	public function get_post_by_id( $id ) {
		$query = file_get_contents( Pronamic_Importer_Plugin::$dirname . '/includes/sql/project-2/select-post-by-id.sql' );

		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':id', $id, PDO::PARAM_INT);
		$statement->execute();

		$content = $statement->fetchAll(PDO::FETCH_OBJ);
		$content = array_shift($content);

		return $content;
	}

	public function get_images_for_post_id( $id ) {
		$query = file_get_contents( Pronamic_Importer_Plugin::$dirname . '/includes/sql/project-2/select-images-by-post-id.sql' );
	
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':id', $id, PDO::PARAM_INT);
		$statement->execute();
	
		$content = $statement->fetchAll(PDO::FETCH_OBJ);

		return $content;
	}
}
