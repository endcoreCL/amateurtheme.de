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



if ( ! function_exists( 'at_import_pornme_get_video_count' ) ) {
	/**
	 * at_import_pornme_get_video_count
	 *
	 * @param $source_id
	 * @param bool $imported
	 * @return string
	 */
	function at_import_pornme_get_video_count($source_id) {
		global $wpdb;

		$database = new AT_Import_PornMe_DB();

		$videos = $wpdb->get_var('SELECT COUNT(id) as count FROM ' . $database->table_videos . ' WHERE source_id = ' . $source_id);

		if($videos) {
			return $videos;
		}

		return '0';
	}
}