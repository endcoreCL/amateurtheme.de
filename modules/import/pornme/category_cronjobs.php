<?php
/**
 * Loading pornme category cronjob functions
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if ( ! function_exists( 'at_import_pornme_category_cronjob_initiate' ) ) {
	/**
	 * at_import_pornme_category_cronjob_initiate function
	 *
	 */
	add_action('at_import_cronjob_edit', 'at_import_pornme_category_cronjob_initiate', 10, 3);
	function at_import_pornme_category_cronjob_initiate($id, $field, $value) {
		if ($field == 'scrape') {
			global $wpdb;

			$cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

			if ($cron) {
				if ($cron->network == 'pornme' && $cron->type == 'category') {
					if ($value == '1') {
						if (!wp_next_scheduled('at_import_pornme_scrape_category_videos_cronjob', array($id))) {
							wp_schedule_event(time(), 'daily', 'at_import_pornme_scrape_category_videos_cronjob', array($id));
						}
					} else {
						wp_clear_scheduled_hook('at_import_pornme_scrape_category_videos_cronjob', array($id));
					}
				}
			}
		}
	}
}

if ( ! function_exists( 'at_import_pornme_category_cronjob_delete' ) ) {
	/**
	 * at_import_pornme_category_cronjob_delete function
	 *
	 */
	add_action('at_import_cronjob_delete', 'at_import_pornme_category_cronjob_delete', 10, 1);
	function at_import_pornme_category_cronjob_delete($id) {
		global $wpdb;

		$cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

		if ($cron) {
			if ($cron->network == 'pornme' && $cron->type == 'category') {
				wp_clear_scheduled_hook('at_import_pornme_scrape_category_videos_cronjob', array($id));
			}
		}
	}
}

if ( ! function_exists( 'at_import_pornme_scrape_category_videos_cronjob' ) ) {
	/**
	 * at_import_pornme_scrape_category_videos_cronjob function
	 *
	 */
	add_action('at_import_pornme_scrape_category_videos_cronjob', 'at_import_pornme_scrape_category_videos_cronjob');
	function at_import_pornme_scrape_category_videos_cronjob($id) {
		global $wpdb;
		$cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

		at_error_log(print_r($cron, true));

		if(!$cron) {
			wp_clear_scheduled_hook('at_import_pornme_scrape_category_videos_cronjob', array($id));
			at_error_log('Cron ' . $id . ' deleted');
			exit;
		}

		$results = array('created' => 0, 'skipped' => 0, 'total' => 0, 'last_updated' => '');

		at_error_log('Started cronjob (PornME, Crawl ' . $id . ')');

		if ($cron) {
			$category = $cron->object_id;

			// initiate import
			$import = new AT_Import_PornMe_Crawler();

			$num_pages = $import->getNumPages('video', array('cat' => $category));

			if ($num_pages !== false) {
				$wpdb->update(
					AT_CRON_TABLE,
					array(
						'processing' => 1,
					),
					array(
						'id' => $id
					)
				);

				for ($i = 0; $i <= $num_pages; $i++) {
					$result = $import->jsonVideosByTag($category, $i);

					if(isset($result['videos'])) {
						$data = json_decode($import->saveVideos($category, $result['videos'], 'category'), true );

						if ( $data['created'] ) {
							$results['created'] = $results['created'] + $data['created'];
						}

						if ( $data['skipped'] ) {
							$results['skipped'] = $results['skipped'] + $data['skipped'];
						}

						if ( $data['total'] ) {
							$results['total'] = $results['total'] + $data['total'];
						}
					}

					$results['last_activity'] = date("d.m.Y H:i:s");
				}
			}

			$wpdb->update(
				AT_CRON_TABLE,
				array(
					'created' => $results['created'],
					'skipped' => $results['skipped'],
					'processing' => 0,
					'last_activity' => date("Y-m-d H:i:s")
				),
				array(
					'object_id' => $category
				)
			);
		}

		at_error_log(print_r($results,true));

		at_error_log('Stoped cronjob (PornMe, ' . $id . ')');

		at_write_api_log('pornme', $cron->name, 'Total: ' . $results['total'] . ', Imported: ' . $results['created'] . ' Skipped: ' . $results['skipped']);

		echo json_encode($results);
	}
}