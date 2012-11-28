DELETE FROM wp_comments WHERE comment_post_ID = 0;
DELETE FROM wp_commentmeta WHERE comment_id NOT IN ( SELECT comment_ID FROM wp_comments );