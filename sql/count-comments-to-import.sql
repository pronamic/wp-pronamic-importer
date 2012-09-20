SELECT
	COUNT(reactie.idforumreacties)
FROM
	forumreacties AS reactie
		LEFT JOIN
	gebruikers AS gebruiker
			ON reactie.gebruikers_idgebruikers = gebruiker.idgebruikers
		LEFT JOIN
	gebruikersdata AS profiel
			ON gebruiker.idgebruikers = profiel.gebruikers_idgebruikers
		LEFT JOIN
	forumposts AS post
			ON reactie.forumposts_idforumposts = post.idforumposts
		LEFT JOIN
	content AS content
			ON post.content_idcontent = content.idcontent
		LEFT JOIN
	types AS type
			ON content.types_idtypes = type.idtypes
WHERE
	reactie.wordpress_imported = 0
		AND
	reactie.wordpress_failed = 0
		AND
	post.content_idcontent IS NOT NULL
		AND
	type.naamtypes = "nieuws"
;