SELECT
	COUNT(media.id)
FROM
	media
WHERE
	media.type = "image"
		AND
	media.wordpress_imported = 0
		AND
	media.wordpress_failed = 0 