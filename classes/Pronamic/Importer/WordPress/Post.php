<?php

class Pronamic_Importer_WordPress_Post {
    
    public static function map( ImportInfo $import_info, WP_Post $post ) {
        
        $import_info->setPostData( 'post_author', $post->post_author );
        $import_info->setPostData( 'post_date', $post->post_date );
        $import_info->setPostData( 'post_date_gmt', $post->post_date_gmt );
        $import_info->setPostData( 'post_content', $post->post_content );
        $import_info->setPostData( 'post_title', $post->post_title );
        $import_info->setPostData( 'post_excerpt', $post->post_excerpt );
        $import_info->setPostData( 'post_status', $post->post_status );
        $import_info->setPostData( 'comment_status', $post->comment_status );
        $import_info->setPostData( 'ping_status', $post->ping_status );
        $import_info->setPostData( 'post_password', $post->post_password );
        $import_info->setPostData( 'post_name', $post->post_name );
        $import_info->setPostData( 'to_ping', $post->to_ping );
        $import_info->setPostData( 'pinged', $post->pinged );
        $import_info->setPostData( 'post_modified', $post->post_modified );
        $import_info->setPostData( 'post_modified_gmt', $post->post_modified_gmt );
        $import_info->setPostData( 'post_content_filtered', $post->post_content_filtered );
        $import_info->setPostData( 'post_parent', $post->post_parent );
        $import_info->setPostData( 'guid', $post->guid );
        $import_info->setPostData( 'menu_order', $post->menu_order );
        $import_info->setPostData( 'post_type', $post->post_type );
        $import_info->setPostData( 'post_mime_type', $post->post_mime_type );
        $import_info->setPostData( 'comment_count', $post->comment_count );
        
        return $import_info;
    }
}