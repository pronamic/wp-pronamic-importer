SELECT
	c.comment_ID AS wp_comment_id,
	c.comment_post_ID AS wp_post_id,
	cm.meta_value AS import_comment_post_id,
	pm.meta_value AS import_post_id,
	p.post_type AS wp_post_type
FROM
	wp_comments AS c
		LEFT JOIN
	wp_commentmeta AS cm
			ON (
				c.comment_ID = cm.comment_id
			)
		LEFT JOIN
	wp_postmeta AS pm
			ON (
				pm.meta_value = cm.meta_value 
			)
		LEFT JOIN
	wp_posts AS p
			ON
				p.id = pm.post_id
WHERE
	cm.meta_key = '_import_post_id'
		AND
	pm.meta_key = '_import_id'
		AND
	p.post_type = 'post'
		AND
	c.comment_post_ID = 0
;


SELECT
	c.comment_ID AS wp_comment_id,
	c.comment_post_ID AS wp_post_id,
	cm.meta_value AS import_comment_post_id
FROM
	wp_comments AS c
		INNER JOIN
	wp_commentmeta AS cm
			ON (
				cm.meta_key = '_import_post_id'
					AND
				c.comment_ID = cm.comment_id
			)
		INNER JOIN
	wp_postmeta AS pm
			ON (
				pm.meta_key = '_import_id'
					AND
				pm.meta_value = cm.meta_value
			)
WHERE
	c.comment_post_ID = 0
;

CREATE VIEW import_comment_post_ids AS
	SELECT
		comment_id,
		meta_value AS import_comment_post_id
	FROM
		wp_commentmeta
	WHERE
		meta_key = '_import_post_id'
	;

CREATE VIEW import_post_ids AS
	SELECT
		post_id,
		meta_value AS import_post_id
	FROM
		wp_postmeta
	WHERE
		meta_key = '_import_id'
	;

SELECT
	comment_id,
	post_id
FROM
	import_comment_post_ids
		LEFT JOIN
	import_post_ids
			ON
				import_comment_post_id = import_post_id
;

# Comment post IDs
CREATE TABLE IF NOT EXISTS import_comment_post_ids (
  	comment_id BIGINT(20) UNSIGNED NOT NULL,
	import_comment_post_id BIGINT(20) UNSIGNED NOT NULL,
	PRIMARY KEY (comment_id, import_comment_post_id)
);

INSERT INTO import_comment_post_ids (comment_id, import_comment_post_id)
	SELECT
		comment_id,
		meta_value AS import_comment_post_id
	FROM
		wp_commentmeta
	WHERE
		meta_key = '_import_post_id'
;

# Post IDs
CREATE TABLE IF NOT EXISTS import_post_ids (
  	post_id BIGINT(20) UNSIGNED NOT NULL,
  	post_type VARCHAR(20) NOT NULL,
	import_post_id BIGINT(20) UNSIGNED NOT NULL,
	PRIMARY KEY (post_id, post_type, import_post_id)
);

INSERT INTO import_post_ids (post_id, post_type, import_post_id)
	SELECT
		post.ID,
		post.post_type,
		meta_value AS import_post_id
	FROM
		wp_posts AS post
			LEFT JOIN
		wp_postmeta AS meta
				ON post.ID = meta.post_id 
	WHERE
		meta_key = '_import_id'
;

# Update
UPDATE
	wp_comments AS c
		INNER JOIN
	import_comment_post_ids AS ic
			ON c.comment_ID = ic.comment_id
		INNER JOIN
	import_post_ids AS ip
			ON ic.import_comment_post_id = ip.import_post_id
SET
	c.comment_post_ID = ip.post_id
WHERE
	c.comment_post_ID = 0
;

# Fix count
CREATE TEMPORARY TABLE wp_posts_comment_count AS
	SELECT 
		comment_post_ID, 
		COUNT(comment_ID) AS comment_count 
	FROM 
		wp_comments 
	WHERE 
		comment_approved 
	GROUP BY 
		comment_post_ID

UPDATE
	wp_posts AS post
		INNER JOIN
	wp_posts_comment_count AS comment_count
			ON post.ID = comment_count.comment_post_ID
SET 
	post.comment_count = comment_count.comment_count
