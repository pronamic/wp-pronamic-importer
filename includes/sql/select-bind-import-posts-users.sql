SELECT
	post.ID AS post_ID,
	post.post_title,
	post.post_author,

	pm.meta_key,
	pm.meta_value,

	um.user_id,
	um.meta_key,
	um.meta_value
FROM
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
;