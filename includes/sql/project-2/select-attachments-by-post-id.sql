SELECT
	image.img_id AS import_id,  
	image.img_name AS import_title, 
	image.img_descr AS import_content,
	CONCAT('http://www.bakkeveen.nl/i/items/', image.img_id, '.jpg') AS import_url
FROM
	images AS image
WHERE
	image.img_nws_id = :id
ORDER BY
	image.img_volgnummer
;