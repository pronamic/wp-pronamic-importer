SELECT
	comment.id AS import_id,
	comment.timestamp AS import_date,
	comment.reaction AS import_content,
	comment.item AS import_post_id,
	comment.name AS import_author,
	comment.ip AS import_ip,
	comment.trash AS import_trash
FROM
	reactions AS comment
WHERE
	wordpress_imported = 0
		AND
	wordpress_import_attempts = 0
LIMIT
	0, 500
;