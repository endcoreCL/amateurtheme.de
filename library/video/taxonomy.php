<?php
/*
 * Taxonomy
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	video
 */

add_action( 'init', 'video_taxonomies', 0 );
function video_taxonomies() {
    register_taxonomy(
        'video_category',
        'video',
        array(
            'label' => __('Kategorie', 'xcore'),
            'rewrite' => array( 'slug' =>  'kategorie', 'with_front' => true, 'hierarchical' => true),
            'hierarchical' => true,
            'query_var' => true,
            'sort' => true,
            'public' => true,
        )
    );

    register_taxonomy(
        'video_actor',
        'video',
        array(
            'label' => __('Darsteller', 'xcore'),
            'rewrite' => array( 'slug' =>  'darsteller', 'with_front' => true, 'hierarchical' => true),
            'hierarchical' => true,
            'query_var' => true,
            'sort' => true,
            'public' => true,
        )
    );

    flush_rewrite_rules();
}