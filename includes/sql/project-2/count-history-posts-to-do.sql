SELECT
	COUNT(hd.id) 
FROM
	historie_data AS hd
WHERE
	wordpress_imported = 0
		AND
	wordpress_import_attempts = 0
;