<?php
/**
 * enqueue_scripts
 *
 * @author        Christian Lang
 * @version        1.0
 * @category    helper
 */

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'theme', get_template_directory_uri() . '/style.css', '', AT_VERSION );

    /* fonts */
    wp_enqueue_style( 'font_open_sans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,700' );

    /* popper.js */
    if ( get_field( 'src_popper', 'options' ) ) {
        wp_enqueue_script( 'popper', get_template_directory_uri() . '/assets/js/bootstrap/popper.min.js', array( 'jquery' ), '1.12.3', false );
    }

    /* bootstrap.js */
    if ( get_field( 'src_bootstrap', 'options' ) ) {
        wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap/bootstrap.min.js', array( 'popper' ), '4.3.1', false );
    }

    /* mobile-detect.js */
    if ( get_field( 'src_mobile_detect', 'options' ) ) {
        wp_enqueue_script( 'mobile-detect', get_template_directory_uri() . '/assets/js/mobile-detect/mobile-detect.min.js', array( 'popper' ), '1.3.7', false );
    }

    /* ouibounce.js */
    wp_enqueue_script( 'ouibounce', get_template_directory_uri() . '/assets/js/ouibounce/ouibounce.min.js', array( 'jquery' ), '1.0', false );

    /* simple-lightbox.js */
    if ( get_field( 'src_lightbox', 'options' ) ) {
        wp_enqueue_script( 'lightbox', get_template_directory_uri() . '/assets/js/simplelightbox/simple-lightbox.min.js', array( 'jquery' ), '1.12.0', false );
    }

    /* Owl */
    wp_enqueue_script( 'owl', get_template_directory_uri() . '/assets/js/owl/owl.carousel.min.js' );
    wp_enqueue_style( 'owl', get_template_directory_uri() . '/assets/css/owl.carousel.min.css' );
    wp_enqueue_style( 'owl-theme', get_template_directory_uri() . '/assets/css/owl.theme.default.min.css' );

    /* custom.js */
    wp_enqueue_script( 'at-custom', get_template_directory_uri() . '/assets/js/amateurtheme/custom.js', array( 'bootstrap' ), AT_VERSION, false );

    /* plyr */
    if ( is_singular( 'video' ) ) {
        wp_enqueue_script( 'plyr', get_template_directory_uri() . '/assets/js/plyr/plyr.min.js' );
        wp_enqueue_style( 'plyr', get_template_directory_uri() . '/assets/css/plyr.min.css' );
        wp_enqueue_script( 'video', get_template_directory_uri() . '/assets/js/amateurtheme/video.js', array( 'plyr' ) );
    }
} );

/**
 * Backend CSS/JS
 */
add_action( 'admin_enqueue_scripts', 'at_load_backend_scripts' );
function at_load_backend_scripts ()
{
    /** CSS */
    wp_enqueue_style( 'at-backend', get_template_directory_uri() . '/assets/css/backend.css', '', AT_VERSION );

    /** JS */
    wp_enqueue_script( 'at-backend', get_template_directory_uri() . '/assets/js/amateurtheme/backend.js' );
}

/**
 * TinyMCE Styles
 */
add_action( 'admin_init', 'at_load_editor_styles' );
function at_load_editor_styles ()
{
    add_editor_style( get_template_directory_uri() . '/assets/css/editor.css' );
}

/**
 * Login Styles
 */
add_action( 'login_enqueue_scripts', 'at_load_login_styles' );
function at_load_login_styles ()
{
    wp_enqueue_style( 'at-login', get_stylesheet_directory_uri() . '/assets/css/login.css' );
}