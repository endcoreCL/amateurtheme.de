<?php
/**
 * Post Type: Video
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	video
 */

add_action( 'init', 'video_register' );
function video_register() {
	$video_archive_deactivate = get_field( 'video_archive_deactivate', 'options' );
	$video_archive_slug = ( get_field( 'video_archive_slug', 'options' ) ? get_field( 'video_archive_slug', 'options' ) : 'videos' );
	$video_single_slug = ( get_field( 'video_single_slug', 'options' ) ? get_field( 'video_single_slug', 'options' ) : 'videos' );

	$labels = array(
        'name' 				=> __( 'Videos', 'amateurtheme' ),
        'singular_name' 	=> __( 'Video', 'amateurtheme' ),
        'add_new' 			=> __( 'Neue Video Erstellen', 'amateurtheme' ),
        'add_new_item' 		=> __( 'Neues Video hinzuf&uuml;gen', 'amateurtheme' ),
        'edit_item' 		=> __( 'Video editieren', 'amateurtheme' ),
        'new_item' 			=> __( 'Neues Video', 'amateurtheme' ),
        'all_items' 		=> __( 'Eintr&auml;ge', 'amateurtheme' ),
        'view_item' 		=> __( 'Zeige Video', 'amateurtheme' ),
        'search_items' 		=> __( 'Suche Video', 'amateurtheme' ),
        'not_found' 		=> __( 'Kein Video gefunden', 'amateurtheme' ),
        'menu_name' 		=> __( 'Videos', 'amateurtheme' )
    );

    $args = array(
        'labels'			=> $labels,
        'public'			=> true,
        'show_ui'			=> true,
        'has_archive'		=> ( $video_archive_deactivate ? false : $video_archive_slug ),
        'hierarchical'		=> false,
        'menu_icon'			=> 'dashicons-video-alt3',
        'rewrite'			=> array( 'slug' => $video_single_slug, 'with_front' => false ),
        'supports' 			=> array( 'title', 'editor', 'thumbnail', 'author', 'custom-fields' )
    );

    register_post_type( 'video', $args );
}