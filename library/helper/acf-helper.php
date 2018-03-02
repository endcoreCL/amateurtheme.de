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
	acf_add_options_sub_page(array('title' => __('Design', 'xcore'), 'parent' => 'acf-options-framework'));
}


/**
 * ACF Design > Topbar Template dropdown
 */
add_filter('acf/load_field/key=field_5a7d99e7b111e', 'xcore_acf_topbar_template_dropdown');
function xcore_acf_topbar_template_dropdown( $field ) {
	// reset choices
	$field['choices'] = array();

	// parse folder
	$parser = new xCORE_Parser();
	$templates = $parser->get_topbar_templates();

	if($templates) {
		foreach($templates as $k => $v) {
			$field['choices'][$k] = $v;
		}
	}

	$field['choices'][''] = __('ausblenden', 'xcore-backend');

	// return the field
	return $field;
}

/**
 * ACF Desgin > Header Template dropdown
 */
add_filter('acf/load_field/key=field_5a7d5c42dc7b0', 'xcore_acf_header_template_dropdown');
function xcore_acf_header_template_dropdown( $field ) {
	// reset choices
	$field['choices'] = array();

	// parse folder
	$parser = new xCORE_Parser();
	$templates = $parser->get_header_templates();

	if($templates) {
		foreach($templates as $k => $v) {
			$field['choices'][$k] = $v;
		}
	}

	$field['choices'][''] = __('ausblenden', 'xcore-backend');

	// return the field
	return $field;
}

/**
 * ACF Design > Teaser Template dropdown
 */
add_filter('acf/load_field/key=field_5a7d6f9fb1497', 'xcore_acf_teaser_template_dropdown');
function xcore_acf_teaser_template_dropdown( $field ) {
	// reset choices
	$field['choices'] = array();

	// parse folder
	$parser = new xCORE_Parser();
	$templates = $parser->get_teaser_templates();

	if($templates) {
		foreach($templates as $k => $v) {
			$field['choices'][$k] = $v;
		}
	}

	$field['choices'][''] = __('ausblenden', 'xcore-backend');

	// return the field
	return $field;
}

/**
 * ACF Design > Content Template dropdown
 */
add_filter('acf/load_field/key=field_5a7d6fb4b1499', 'xcore_acf_content_template_dropdown');
function xcore_acf_content_template_dropdown( $field ) {
	// reset choices
	$field['choices'] = array();

	// parse folder
	$parser = new xCORE_Parser();
	$templates = $parser->get_content_templates();

	if($templates) {
		foreach($templates as $k => $v) {
			$field['choices'][$k] = $v;
		}
	}

	$field['choices'][''] = __('ausblenden', 'xcore-backend');

	// return the field
	return $field;
}

/**
 * ACF Design > Footer Template dropdown
 */
add_filter('acf/load_field/key=field_5a7d6fd4b149b', 'xcore_acf_footer_template_dropdown');
function xcore_acf_footer_template_dropdown( $field ) {
	// reset choices
	$field['choices'] = array();

	// parse folder
	$parser = new xCORE_Parser();
	$templates = $parser->get_footer_templates();

	if($templates) {
		foreach($templates as $k => $v) {
			$field['choices'][$k] = $v;
		}
	}

	$field['choices'][''] = __('ausblenden', 'xcore-backend');

	// return the field
	return $field;
}

/**
 * ACF Design > Topbar > Navigtion dropdown
 */
add_filter('acf/load_field/key=field_5a7db3e724c14', 'xcore_acf_topbar_navigation_dropdown');
function xcore_acf_topbar_navigation_dropdown( $field ) {
	// reset choices
	$field['choices'] = array();

	// get navigations
	$menus = get_terms( 'nav_menu' );
	$menus = array_combine( wp_list_pluck( $menus, 'term_id' ), wp_list_pluck( $menus, 'name' ) );

	if($menus) {
		foreach($menus as $k => $v) {
			$field['choices'][$k] = $v;
		}
	}

	// return the field
	return $field;
}

