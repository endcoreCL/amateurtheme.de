<?php
/**
 * Loading ac ajax functions
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if ( ! function_exists( 'at_import_ac_amateurs_select' ) ) {
	/**
	 * at_import_ac_amateurs_select function
	 *
	 */
	add_action('wp_ajax_at_ac_amateurs', 'at_import_ac_amateurs_select');
	function at_import_ac_amateurs_select() {
		global $wpdb;

		$database = new AT_Import_AC_DB();

		$q = (isset($_GET['q']) ? $_GET['q'] : false);

		if(!$q) {
			die();
		}

		$limit = 500;

		$output = array();

		$items = $wpdb->get_results('SELECT * FROM ' . $database->table_amateurs . ' WHERE nickname LIKE "%' . $q . '%" LIMIT 0,' . $limit);

		if($items) {
			foreach($items as $item) {
				$output[] = array($item->uid, $item->nickname);
			}
		}

		echo json_encode($output);

		die();
	}
}