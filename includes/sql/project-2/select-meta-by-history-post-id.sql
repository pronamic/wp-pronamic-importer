# http://stackoverflow.com/questions/10893580/mysql-convert-column-into-row

	SELECT 
		"_history_post_owner" AS meta_key,
		data.owner AS meta_value
	FROM
		historie_data AS data
	WHERE
		data.id = :id
	
UNION ALL

	SELECT 
		"_history_post_year" AS meta_key,
		data.year AS meta_value
	FROM
		historie_data AS data
	WHERE
		data.id = :id
	
UNION ALL

	SELECT 
		"_history_post_estimate" AS meta_key,
		data.schatting AS meta_value
	FROM
		historie_data AS data
	WHERE
		data.id = :id
	
UNION ALL

	SELECT 
		"_history_post_street" AS meta_key,
		data.street AS meta_value
	FROM
		historie_data AS data
	WHERE
		data.id = :id
	
UNION ALL

	SELECT 
		"_history_post_stars" AS meta_key,
		data.stars AS meta_value
	FROM
		historie_data AS data
	WHERE
		data.id = :id
	
UNION ALL

	SELECT 
		"_history_post_inspection_date" AS meta_key,
		data.checkdate AS meta_value
	FROM
		historie_data AS data
	WHERE
		data.id = :id

;