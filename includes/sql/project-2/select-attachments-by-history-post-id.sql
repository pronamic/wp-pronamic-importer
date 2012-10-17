SELECT
	file.id AS import_id,  
	data.title AS import_title, 
	data.descr AS import_content,
	CONCAT('http://www.bakkeveen.nl/i/historie/', file.name) AS import_url
FROM
	historie_files AS file
		LEFT JOIN
	historie_data AS data
			ON data.file_id = file.id
WHERE
	data.id = :id
;