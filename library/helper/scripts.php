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

    /** popper JS */
    if(get_field('src_popper', 'options')) {
        wp_enqueue_script('popper', get_template_directory_uri() . '/assets/js/popper.min.js', array('jquery'), '1.12.3', false);
    }

    /** Bootstrap JS */
    if(get_field('src_bootstrap', 'options')) {
        wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('popper'), '4.0.0-beta.2', false);
    }

    /** Mobile Detect */
    if(get_field('src_mobile_detect', 'options')) {
        wp_enqueue_script('mobile-detect', get_template_directory_uri() . '/assets/js/mobile-detect.min.js', array('popper'), '1.3.7', false);
    }

    /** Lightbox */
    if(get_field('src_lightbox', 'options')) {
        wp_enqueue_script('lightbox', get_template_directory_uri() . '/assets/js/lightbox.js', array('jquery'), '1.12.0', false);
    }

    /** Custom JS */
    wp_enqueue_script('custom', get_template_directory_uri() . '/assets/js/custom.js', array('bootstrap'), '1.0', false);
}

/**
 * Backend CSS
 */
add_action('admin_enqueue_scripts', 'xcore_load_backend_scripts');
function xcore_load_backend_scripts() {
    wp_enqueue_style('xcore-backend', get_template_directory_uri() . '/assets/css/backend.css');

}