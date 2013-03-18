# http://stackoverflow.com/questions/10893580/mysql-convert-column-into-row

	SELECT 
		"post_tag" AS taxonomy,
		data.tag AS term
	FROM
		tags AS data
	WHERE
		data.pageID = :id

;