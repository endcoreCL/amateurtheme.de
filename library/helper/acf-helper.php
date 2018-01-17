<?php
/**
 * ACF Funktionen
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

define( 'ACF_LITE' , false );

add_filter('acf/settings/path', 'xcore_acf_settings_path');
function xcore_acf_settings_path( $path ) {
    $path = XCORE_PLUGINS . '/acf/core';
    return $path;
}

add_filter('acf/settings/dir', 'xcore_acf_settings_dir');
function xcore_acf_settings_dir( $dir ) {
    $dir = get_template_directory_uri() . '/library/plugins/acf/core/';
    return $dir;
}

/**
 * Update-Notification deaktivieren
 */
add_filter('acf/settings/show_updates', '__return_false');

/**
 * ACF Option Pages
 */
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array('page_title' => __('Framework', 'xcore'), 'menu_title' => 'Framework', 'icon_url' => 'dashicons-heart', 'capability' => 'manage_options'));
    acf_add_options_sub_page(array('title' => __('Allgemein', 'xcore'), 'parent' => 'acf-options-framework'));
}