<?php
/**
 * Post Type: Video
 *
 * @author        Christian Lang
 * @version        1.0
 * @category    video
 */

add_action( 'init', function () {
    $video_archive_deactivate = get_field( 'video_archive_deactivate', 'options' );
    $video_archive_slug       = ( get_field( 'video_archive_slug', 'options' ) ? get_field( 'video_archive_slug', 'options' ) : 'videos' );
    $video_single_slug        = ( get_field( 'video_single_slug', 'options' ) ? get_field( 'video_single_slug', 'options' ) : 'videos' );

    $labels = array(
        'name'          => __( 'Videos', 'amateurtheme' ),
        'singular_name' => __( 'Video', 'amateurtheme' ),
        'menu_name'     => __( 'Videos', 'amateurtheme' )
    );

    $args = array(
        'labels'       => $labels,
        'public'       => true,
        'show_ui'      => true,
        'has_archive'  => ( $video_archive_deactivate ? false : $video_archive_slug ),
        'hierarchical' => false,
        'menu_icon'    => 'dashicons-video-alt3',
        'rewrite'      => array( 'slug' => $video_single_slug, 'with_front' => false ),
        'supports'     => array( 'title', 'editor', 'thumbnail', 'author', 'custom-fields' )
    );

    register_post_type( 'video', $args );
} );