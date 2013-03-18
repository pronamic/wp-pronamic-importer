SELECT
	p.id AS import_id,
	pl.title AS import_title,
	CONCAT( "http://www.bestelauto.nl/", p.id, "-nieuwsbericht.html" ) AS import_url,
	ti.text AS import_content,
	UNIX_TIMESTAMP( p.date ) AS import_date,
	"1" AS import_author_id,
	"post" AS import_type,
	"" AS import_category_name	
FROM
	pages AS p
		LEFT JOIN
	pageLang AS pl
			ON p.id = pl.pageID
		LEFT JOIN
	textItems AS ti
			ON p.id = ti.pageID
WHERE
	p.status = 1
		AND
	p.template = "TextPage"
		AND
	p.id = :id
ORDER BY
	p.date ASC
LIMIT
	0, 100
;