SELECT
	COUNT(news.nws_id) 
FROM
	nieuws AS news
		LEFT JOIN
	category AS category
			ON news.nws_category = category.cat_id
WHERE
	nws_url = ''
		AND
	nws_descr != ''
		AND
	nws_timestamp IS NOT NULL 
		AND
	wordpress_imported = 0
		AND
	wordpress_import_attempts = 0
;