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
        if (($query->is_tax('video_actor') || $query->is_tax('video_category') || $query->is_tax('video_tags')) && $query->is_main_query()) {
            $query->set('posts_per_page', 12);
        }

        return $query;
    }
}