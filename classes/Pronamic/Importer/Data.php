<?php

class Pronamic_Importer_Data {
	private $pdo;

	public function __construct($pdo) {
		$this->pdo = $pdo;		
	}

	public function get_post_by_id( $query, $id ) {
		$statement = $this->pdo->prepare( $query );
		$statement->bindValue( ':id', $id, PDO::PARAM_INT );
		$statement->execute();

		$content = $statement->fetchAll( PDO::FETCH_OBJ );
		$content = array_shift( $content );

		return $content;
	}

	public function get_attachments_for_post_id( $query, $id ) {	
		$statement = $this->pdo->prepare( $query );
		$statement->bindValue( ':id', $id, PDO::PARAM_INT );
		$statement->execute();
	
		$content = $statement->fetchAll( PDO::FETCH_OBJ );

		return $content;
	}

	public function get_meta_for_post_id( $query, $id ) {	
		$statement = $this->pdo->prepare( $query );
		$statement->bindValue( ':id', $id, PDO::PARAM_INT );
		$statement->execute();
	
		$content = $statement->fetchAll( PDO::FETCH_OBJ );

		return $content;
	}

	public function get_terms_for_post_id( $query, $id ) {	
		$statement = $this->pdo->prepare( $query );
		$statement->bindValue( ':id', $id, PDO::PARAM_INT );
		$statement->execute();
	
		$content = $statement->fetchAll( PDO::FETCH_OBJ );

		return $content;
	}
}
