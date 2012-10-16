SELECT
	COUNT(content.idcontent)
FROM
	content AS content
		LEFT JOIN
	types AS type
			ON content.types_idtypes = type.idtypes
WHERE
	content.kop != ""
		AND
	content.addeddate IS NOT NULL
		AND
	content.wordpress_imported = 0
		AND
	content.wordpress_failed = 0
		AND
	type.naamtypes = "artikelen" 