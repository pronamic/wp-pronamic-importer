SELECT
	COUNT(media.id)
FROM
	media
WHERE
	media.type = "video"
		AND
	media.wordpress_imported = 0
		AND
	media.wordpress_failed = 0 