<?php
/*
 * Hilfsfunktionen
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	video
 */

add_image_size('video_grid', 345, 194, true);

if ( ! function_exists('at_video_taxonomy_args') ) {
    /**
     * edit taxonomy archive query for video_actor / video_category / video_tags
     */
    add_filter('pre_get_posts', 'at_video_taxonomy_args');
    function at_video_taxonomy_args($query) {
        if ( $query->is_tax('video_tags')  && $query->is_main_query() ) {
	        $posts_per_page = ( get_field( 'video_tags_posts_per_page', 'options' ) ? get_field( 'video_tags_posts_per_page', 'options' ) : 12 );
	        $query->set( 'posts_per_page', $posts_per_page );
        }

	    if ( $query->is_tax('video_category') && $query->is_main_query() ) {
		    $posts_per_page = ( get_field( 'video_category_posts_per_page', 'options' ) ? get_field( 'video_category_posts_per_page', 'options' ) : 12 );
		    $query->set( 'posts_per_page', $posts_per_page );
	    }

        if ( $query->is_tax('video_actor') && $query->is_main_query() ) {
        	$posts_per_page = ( get_field( 'video_actor_posts_per_page', 'options' ) ? get_field( 'video_actor_posts_per_page', 'options' ) : 12 );
        	$query->set( 'posts_per_page', $posts_per_page );
        }

        return $query;
    }
}

if ( ! function_exists('at_video_set_post_view') ) {
	/**
	 * function to set video views
	 */
	add_action( 'wp_ajax_video_views', 'at_video_set_post_view' );
	add_action( 'wp_ajax_nopriv_video_views', 'at_video_set_post_view' );
	function at_video_set_post_view() {
		$post_id = $_POST['post_id'];

		$views = get_field( 'video_views', $post_id );

		if ( $views ) {
			$views += 1;
		} else {
			$views = 1;
		}

		update_field( 'video_views', $views, $post_id );

		exit;
	}
}