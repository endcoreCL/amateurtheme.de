<?php
/**
 * Taxonomy
 *
 * @author        Christian Lang
 * @version        1.0
 * @category    video
 */

add_action( 'init', function () {
    register_taxonomy(
        'video_category',
        'video',
        array(
            'label'        => __( 'Kategorie', 'amateurtheme' ),
            'rewrite'      => array( 'slug' => 'kategorie', 'with_front' => true, 'hierarchical' => true ),
            'hierarchical' => true,
            'query_var'    => true,
            'sort'         => true,
            'public'       => true,
        )
    );

    register_taxonomy(
        'video_tags',
        'video',
        array(
            'label'        => __( 'Schlagwörter', 'amateurtheme' ),
            'rewrite'      => array( 'slug' => 'tags', 'with_front' => true, 'hierarchical' => true ),
            'hierarchical' => true,
            'query_var'    => true,
            'sort'         => true,
            'public'       => true,
        )
    );

    register_taxonomy(
        'video_actor',
        'video',
        array(
            'label'        => __( 'Darsteller', 'amateurtheme' ),
            'rewrite'      => array( 'slug' => 'darsteller', 'with_front' => true, 'hierarchical' => true ),
            'hierarchical' => true,
            'query_var'    => true,
            'sort'         => true,
            'public'       => true,
        )
    );

    flush_rewrite_rules();
} );