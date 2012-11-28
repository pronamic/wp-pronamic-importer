SELECT
	hd.id AS import_id,
	hd.title AS import_title,
	CONCAT( "http://www.bakkeveen.nl/historie/item/", hd.id, "/item.html" ) AS import_url,
	hd.descr AS import_content,
	hd.timestamp AS import_date,
	hd.poster AS import_author_id,
	"history_post" AS import_type,
	"" AS import_category_name
FROM 
	historie_data AS hd
WHERE
	wordpress_imported = 0
		AND
	wordpress_import_attempts = 0
ORDER BY
	hd.timestamp ASC
LIMIT
	0, 100
;