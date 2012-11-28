SELECT
	comment.id AS import_id,
	comment.timestamp AS import_date,
	comment.reaction AS import_content,
	comment.item AS import_post_id,
	comment.name AS import_author,
	comment.ip AS import_ip,
	comment.trash AS import_trash
FROM
	reactionsHistorie AS comment
LIMIT
	0, 100
;