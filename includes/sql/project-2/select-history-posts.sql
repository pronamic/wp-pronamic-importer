SELECT
	hd.id AS post_id,
	hd.title AS post_title,
	CONCAT( "http://www.bakkeveen.nl/historie/item/", hd.id, "/item.html" ) AS post_guid,
	hd.descr AS post_content,
	hd.timestamp AS post_date,
	"history_post" AS post_type,
	"" AS category_name
FROM 
	historie_data AS hd
ORDER BY
	hd.timestamp ASC
LIMIT
	0, 100
;