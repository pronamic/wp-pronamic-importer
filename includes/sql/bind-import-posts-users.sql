UPDATE
	wp_posts AS post
		INNER JOIN
	wp_postmeta AS pm
			ON (
				post.ID = pm.post_id
					AND
				pm.meta_key = '_import_author_id'
			)
		INNER JOIN
	wp_usermeta AS um
			ON (
				um.meta_value = pm.meta_value 
					AND
				um.meta_key = '_import_id'
			)
SET
	post.post_author = um.user_id
;