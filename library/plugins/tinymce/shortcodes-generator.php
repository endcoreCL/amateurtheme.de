<?php
/**
 * Affiliate Theme Shortcode Generator
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	tinymce
 */

add_action('admin_head', 'at_tinymce_addbuttons');
function at_tinymce_addbuttons() {
	global $typenow;

	if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
		return;
	}

	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "at_shortcodes_tinymce_plugin");
		add_filter('mce_buttons', 'at_register_shortcodes_button');
	}
}

function at_shortcodes_tinymce_plugin($plugin_array) {
	$plugin_array['at_tinymce_addbuttons'] = esc_url( get_template_directory_uri() ) . '/library/plugins/tinymce/shortcodes.js';
	return $plugin_array;
}

function at_register_shortcodes_button($buttons) {
	array_push($buttons, "at_tinymce_addbuttons");
	return $buttons;
}

add_filter('admin_enqueue_scripts', 'at_add_shortcodes_scripts');
function at_add_shortcodes_scripts() {
    wp_register_script('at_shortcode_listbox', get_template_directory_uri() . '/library/plugins/tinymce/_/js/ui.multiselect.js', 'jquery-ui-widegt', '1.0', true);
    wp_enqueue_style('at_shortcode_ui_css', get_template_directory_uri() . '/library/plugins/tinymce/_/css/jquery.ui.min.css');

    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-widget');
    wp_enqueue_script('at_shortcode_listbox');
}
?>