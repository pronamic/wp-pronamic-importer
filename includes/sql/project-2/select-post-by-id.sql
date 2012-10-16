SELECT
	news.nws_id AS import_id,
	news.nws_title AS import_title,
	news.nws_url AS import_url,
	news.nws_descr AS import_content,
	news.nws_timestamp AS import_date,
	news.nws_poster AS import_author_id,
	"post" AS import_type,
	news.nws_category AS import_category_id,
	category.cat_title AS import_category_name
FROM
	nieuws AS news
		LEFT JOIN
	category AS category
			ON news.nws_category = category.cat_id
WHERE
	news.nws_id = :id
;