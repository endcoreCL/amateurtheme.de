<?php
/**
 * Loading pornme import helper functions
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if ( ! function_exists( 'at_import_pornme_scripts' ) ) {
	/**
	 * at_import_pornme_scripts function
	 *
	 */
	add_action('admin_enqueue_scripts', 'at_import_pornme_scripts');
	function at_import_pornme_scripts($page) {
		if (strpos($page, 'at_import_pornme') === false) return;

		wp_enqueue_script('at-pornme', get_template_directory_uri() . '/modules/import/_assets/js/pornme.js');
	}
}