SELECT
	COUNT(reactie.id)
FROM
	media_reacties AS reactie
		LEFT JOIN
	gebruikers AS gebruiker
			ON reactie.userid = gebruiker.idgebruikers
		LEFT JOIN
	gebruikersdata AS profiel
			ON gebruiker.idgebruikers = profiel.gebruikers_idgebruikers
		LEFT JOIN
	media AS media
			ON reactie.mediaid = media.id
WHERE
	reactie.wordpress_imported = 0
		AND
	reactie.wordpress_failed = 0
		AND
	media.type = "image"