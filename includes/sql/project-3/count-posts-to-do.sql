SELECT
	COUNT(p.id) 
FROM
	pages AS p
WHERE
	p.status = 1
		AND
	p.template = "TextPage"
		AND
	p.wordpress_imported = 0
		AND
	p.wordpress_import_attempts = 0
;