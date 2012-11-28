SELECT
	COUNT(r.id) 
FROM
	reactions AS r
WHERE
	wordpress_imported = 0
		AND
	wordpress_import_attempts = 0
;