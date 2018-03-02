<?php
/**
 * enqueue_scripts
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

add_action('wp_enqueue_scripts', 'xcore_load_theme_scripts');
function xcore_load_theme_scripts() {
	wp_enqueue_style('theme', get_template_directory_uri() . '/style.css');

	/* fonts */
	wp_enqueue_style('font_open_sans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,700' );
	//wp_enqueue_style('font_montserrat', 'https://fonts.googleapis.com/css?family=Montserrat:400,700,900' );

	/* popper.js */
	if(get_field('src_popper', 'options')) {
		wp_enqueue_script('popper', get_template_directory_uri() . '/assets/js/popper.min.js', array('jquery'), '1.12.3', false);
	}

	/* bootstrap.js */
	if(get_field('src_bootstrap', 'options')) {
		wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('popper'), '4.0.0-beta.2', false);
	}

	/* mobile-detect.js */
	if(get_field('src_mobile_detect', 'options')) {
		wp_enqueue_script('mobile-detect', get_template_directory_uri() . '/assets/js/mobile-detect.min.js', array('popper'), '1.3.7', false);
	}

	/* simple-lightbox.js */
	if(get_field('src_lightbox', 'options')) {
		wp_enqueue_script('lightbox', get_template_directory_uri() . '/assets/js/lightbox.js', array('jquery'), '1.12.0', false);
	}

	/* custom.js */
	wp_enqueue_script('custom', get_template_directory_uri() . '/assets/js/custom.js', array('bootstrap'), '1.0', false);
}

/**
 * Backend CSS/JS
 */
add_action('admin_enqueue_scripts', 'xcore_load_backend_scripts');
function xcore_load_backend_scripts() {
	/** CSS */
	wp_enqueue_style('xcore-backend', get_template_directory_uri() . '/assets/css/backend.css');

	/** JS */
	wp_enqueue_script('xcore-backend', get_template_directory_uri() . '/assets/js/backend.js');
}

/**
 * TinyMCE Styles
 */
add_action('admin_init', 'xcore_load_editor_styles');
function xcore_load_editor_styles() {
	add_editor_style(get_template_directory_uri() . '/assets/css/editor.css' );
}

/**
 * Login Styles
 */
add_action('login_enqueue_scripts', 'xcore_load_login_styles');
function xcore_load_login_styles() {
	wp_enqueue_style( 'xcore-login', get_stylesheet_directory_uri() . '/assets/css/login.css' );
}