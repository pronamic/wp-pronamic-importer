<?php

function bouwmedia_term_exists($term, $taxonomy = '', $parent = 0) {
	global $wpdb;

	$select = "SELECT term_id FROM $wpdb->terms as t WHERE ";
	$tax_select = "SELECT tt.term_id, tt.term_taxonomy_id FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy as tt ON tt.term_id = t.term_id WHERE ";

	if ( is_int($term) ) {
		if ( 0 == $term )
			return 0;
		$where = 't.term_id = %d';
		if ( !empty($taxonomy) )
			return $wpdb->get_row( $wpdb->prepare( $tax_select . $where . " AND tt.taxonomy = %s", $term, $taxonomy ), ARRAY_A );
		else
			return $wpdb->get_var( $wpdb->prepare( $select . $where, $term ) );
	}

	$term = trim( stripslashes( $term ) );

	if ( '' === $slug = sanitize_title($term) )
		return 0;

	$where = 't.slug = %s';
	$else_where = 't.name = %s';
	$where_fields = array($slug);
	$else_where_fields = array($term);
	if ( !empty($taxonomy) ) {
		$parent = (int) $parent;
		//if ( $parent > 0 ) {
			$where_fields[] = $parent;
			$else_where_fields[] = $parent;
			$where .= ' AND tt.parent = %d';
			$else_where .= ' AND tt.parent = %d';
		//}

		$where_fields[] = $taxonomy;
		$else_where_fields[] = $taxonomy;

		if ( $result = $wpdb->get_row( $wpdb->prepare("SELECT tt.term_id, tt.term_taxonomy_id FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy as tt ON tt.term_id = t.term_id WHERE $where AND tt.taxonomy = %s", $where_fields), ARRAY_A) )
			return $result;

		return $wpdb->get_row( $wpdb->prepare("SELECT tt.term_id, tt.term_taxonomy_id FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy as tt ON tt.term_id = t.term_id WHERE $else_where AND tt.taxonomy = %s", $else_where_fields), ARRAY_A);
	}

	if ( $result = $wpdb->get_var( $wpdb->prepare("SELECT term_id FROM $wpdb->terms as t WHERE $where", $where_fields) ) )
		return $result;

	return $wpdb->get_var( $wpdb->prepare("SELECT term_id FROM $wpdb->terms as t WHERE $else_where", $else_where_fields) );
}

class SetPostTerms extends ImportAction {
	public function __construct($append = false) {
		$this->append = $append;
	}

	public function terms(array $terms) {
		foreach($terms as $termInfo) {
			$parentId = 0;
			if(isset($termInfo->parent)) {
				$parentId = $termInfo->parent->id;
			}

			$result = bouwmedia_term_exists($termInfo->name, $termInfo->taxonomy, $parentId);

			if(empty($result)) {
				$result = wp_insert_term(
					$termInfo->name , 
					$termInfo->taxonomy , 
					array(
						'parent' => $parentId
					)
				);
			}

			if(is_wp_error($result)) {
				echo '<pre>';
				var_dump($termInfo);
				var_dump($result);
				echo '</pre>';
			} else {
				$termInfo->id = $result['term_id'];
			}

			$this->terms($termInfo->children);
		}
	}
	
	public function getIds($terms, $ids = array()) {
		foreach($terms as $term) {
			$ids[] = $term->id;

			$ids = $this->getIds($term->children, $ids);
		}

		return $ids;
	}

	public function process(ImportInfo $import) {
		$postId = $import->getPostId();

		foreach($import->taxonomies as $taxonomy => $terms) {
			$this->terms($terms);
		}

		foreach($import->taxonomies as $taxonomy => $terms) {
			$ids = $this->getIds($terms);

			$import->log(sprintf('Setting the post terms: <em>%s</em> => <strong>%s</strong>', $taxonomy, implode(', ', $ids)));

			$affectedTermIds = wp_set_post_terms($postId, $ids, $taxonomy, $this->append);

			if(is_wp_error($affectedTermIds)) {
				$import->log(sprintf('Failed to store the terms.'));
			} else {
				$import->log(sprintf('Succesfully stored the terms, affected term IDs: %s', implode(', ', $affectedTermIds)));
			}
		}

		$this->next($import);
	}
}
