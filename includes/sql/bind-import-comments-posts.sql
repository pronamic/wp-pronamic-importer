UPDATE
	wp_comments AS c
		INNER JOIN
	wp_commentmeta AS cm
			ON (
				c.comment_ID = cm.comment_id
					AND
				cm.meta_key = '_import_post_id'
			)
		INNER JOIN
	wp_postmeta AS pm
			ON (
				pm.meta_value = cm.meta_value 
					AND
				pm.meta_key = '_import_id'
			)
		INNER JOIN
	wp_posts AS post
			ON
				post.id = pm.post_id
					AND
				post.post_type = 'post'
SET
	c.comment_post_ID = pm.post_id
WHERE
	c.comment_post_ID = 0
;