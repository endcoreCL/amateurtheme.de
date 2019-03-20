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

if ( ! function_exists( 'at_video_category_related_tags' ) ) {
	/**
	 * A simple function to get related tags for a category
	 *
	 * @param $category
	 * @param $id
	 *
	 * @return array
	 */
	function at_video_category_related_tags( $category, $id ) {
		if ( false === ( $tags = get_transient( 'cat_' . $id . '_tags' ) ) ) {
			// related tags
			$titles = explode( ' ', $category );
			$tags = array();

			if ( $titles ) {
				foreach ( $titles as $title ) {
					$args = array(
						'hide_empty' => true,
						'number' => 10,
						'name__like' => $title
					);

					$tags_tmp = get_terms( 'video_tags', $args );

					if ( $tags_tmp ) {
						$tags = array_merge ( $tags, $tags_tmp );
					}
				}

				if ( $tags ) {
					$tags = array_slice( $tags, 0, 10 );
					$exp = apply_filters( 'at_video_category_related_tags_transient_expiration', 12 * HOUR_IN_SECONDS );
					set_transient( 'cat_' . $id . '_tags', $tags, $exp );
				}
			}
		}

		return $tags;
	}
}