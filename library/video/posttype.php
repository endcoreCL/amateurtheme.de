<?php
/*
 * Post Type: Video
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	video
 */
add_action('init', 'video_register');
function video_register() {
    $labels = array(
        'name' 				=> __('Videos', 'xcore'),
        'singular_name' 	=> __('Video', 'xcore'),
        'add_new' 			=> __('Neue Video Erstellen', 'xcore'),
        'add_new_item' 		=> __('Neues Video hinzuf&uuml;gen', 'xcore'),
        'edit_item' 		=> __('Video editieren', 'xcore'),
        'new_item' 			=> __('Neues Video', 'xcore'),
        'all_items' 		=> __('Eintr&auml;ge', 'xcore'),
        'view_item' 		=> __('Zeige Video', 'xcore'),
        'search_items' 		=> __('Suche Video', 'xcore'),
        'not_found' 		=> __('Kein Video gefunden', 'xcore'),
        'menu_name' 		=> __('Videos', 'xcore')
    );

    $args = array(
        'labels'			=> $labels,
        'public'			=> true,
        'show_ui'			=> true,
        'has_archive'		=> true,
        'hierarchical'		=> true,
        'menu_icon'			=> 'dashicons-video-alt3',
        'rewrite'			=> array('slug' => 'video', 'with_front' => false),
        'supports' 			=> array('title', 'editor', 'thumbnail', 'author', 'custom-fields')
    );

    register_post_type('video', $args);
}