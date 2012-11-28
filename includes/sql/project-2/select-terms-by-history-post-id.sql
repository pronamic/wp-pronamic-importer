# http://stackoverflow.com/questions/10893580/mysql-convert-column-into-row

	SELECT 
		"location" AS taxonomy,
		data.street AS term
	FROM
		historie_data AS data
	WHERE
		data.street != ""
			AND
		data.id = :id
	
UNION ALL

	SELECT 
		"type" AS taxonomy,
		data.sort AS term
	FROM
		historie_data AS data
	WHERE
		data.sort != ""
			AND
		data.id = :id

;