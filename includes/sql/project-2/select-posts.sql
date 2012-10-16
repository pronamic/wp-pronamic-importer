SELECT
	news.nws_id AS post_id,
	news.nws_title AS post_title,
	news.nws_url AS post_guid,
	news.nws_descr AS post_content,
	news.nws_timestamp AS post_date,
	"post" AS post_type,
	category.cat_title AS category_name
FROM
	nieuws AS news
		LEFT JOIN
	category AS category
			ON news.nws_category = category.cat_id
WHERE
	nws_url = ""
		AND
	nws_descr != ""
		AND
	nws_timestamp IS NOT NULL
		AND
	wordpress_imported = 0
		AND
	wordpress_import_attempts = 0
ORDER BY
	news.nws_timestamp ASC
LIMIT
	0, 100
;