<?php
/**
 * Loading PornMe amateur cronjob functions
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if ( ! function_exists( 'at_import_pornme_amateur_cronjob_initiate' ) ) {
	/**
	 * at_import_pornme_amateur_cronjob_initiate function
	 *
	 */
	add_action('at_import_cronjob_edit', 'at_import_pornme_amateur_cronjob_initiate', 10, 3);
	function at_import_pornme_amateur_cronjob_initiate($id, $field, $value) {
		if ($field == 'scrape') {
			global $wpdb;

			$cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

			if ($cron) {
				if ($cron->network == 'pornme' && $cron->type == 'user') {
					if ($value == '1') {
						if (!wp_next_scheduled('at_import_pornme_scrape_videos_cronjob', array($id))) {
							wp_schedule_event(time(), 'daily', 'at_import_pornme_scrape_videos_cronjob', array($id));
						}
					} else {
						wp_clear_scheduled_hook('at_import_pornme_scrape_videos_cronjob', array($id));
					}
				}
			}
		}

		if ($field == 'import') {
			global $wpdb;

			$cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

			if ($cron) {
				if ($cron->network == 'pornme') {
					if ($value == '1') {
						if (!wp_next_scheduled('at_import_pornme_import_videos_cronjob', array($id))) {
							wp_schedule_event(time(), '30min', 'at_import_pornme_import_videos_cronjob', array($id));
						}
					} else {
						wp_clear_scheduled_hook('at_import_pornme_import_videos_cronjob', array($id));
					}
				}
			}
		}
	}
}

if ( ! function_exists( 'at_import_pornme_scrape_videos_cronjob' ) ) {
	/**
	 * at_import_pornme_scrape_videos_cronjob function
	 *
	 */
	add_action('at_import_pornme_scrape_videos_cronjob', 'at_import_pornme_scrape_videos_cronjob');
	function at_import_pornme_scrape_videos_cronjob($id) {
		global $wpdb;
		$cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

		at_error_log(print_r($cron, true));

		if(!$cron) {
			wp_clear_scheduled_hook('at_import_pornme_scrape_videos_cronjob', array($id));
			at_error_log('Cron ' . $id . ' deleted');
			exit;
		}

		$results = array('created' => 0, 'skipped' => 0, 'total' => 0, 'last_updated' => '');

		at_error_log('Started cronjob (PornME, Crawl ' . $id . ')');

		if ($cron) {
			$uid = $cron->object_id;

			// initiate import
			$import = new AT_Import_PornMe_Crawler();

			$num_pages = $import->getNumPages('video', array('UID' => $uid));

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
					$result = $import->jsonVideos($uid, $i);

					if(isset($result['videos'])) {
						$data = json_decode($import->saveVideos($uid, $result['videos']), true );

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
					'object_id' => $uid
				)
			);
		}

		at_error_log('Stoped cronjob (PornMe, Crawl, ' . $id . ')');

		echo json_encode($results);
	}
}

if ( ! function_exists( 'at_import_pornme_import_videos_cronjob' ) ) {
	/**
	 * at_import_pornme_import_videos_cronjob function
	 *
	 */
	add_action('at_import_pornme_import_videos_cronjob', 'at_import_pornme_import_videos_cronjob');
	function at_import_pornme_import_videos_cronjob($id) {
		set_time_limit(120); // try to set time limit to 120 seconds

		global $wpdb;
		$cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

		if(!$cron) {
			wp_clear_scheduled_hook('at_import_pornme_import_videos_cronjob', array($id));
			at_error_log('Cron ' . $id . ' deleted');
			exit;
		}

		$results = array('created' => 0, 'skipped' => 0, 'total' => 0, 'last_updated' => '');

		at_error_log('Started cronjob (PornMe, ' . $id . ')');

		if ($cron) {
			$database = new AT_Import_PornMe_DB();
			$videos = $wpdb->get_results('SELECT * FROM ' . $database->table_videos . ' WHERE source_id = "' . $cron->object_id . '" AND imported != 1 LIMIT 0,50');

			if ($videos) {
				foreach ($videos as $item) {
					$video = new AT_Import_Video($item->video_id);

					if ($video->unique) {
						// update cron table (processing)
						$wpdb->update(
							AT_CRON_TABLE,
							array(
								'processing' => 1,
							),
							array(
								'id' => $id
							)
						);

						$post_id = $video->insert($item->title, $item->description);

						if ($post_id) {
							// fields
							$fields = at_import_pornme_prepare_video_fields($item->video_id);
							if ($fields) {
								$video->set_fields($fields);
							}

							// thumbnail
							if ($item->preview) {
								$video->set_thumbnail($item->preview);
							}

							// category
							$categories = json_decode($item->tags);
							if ($categories) {
								foreach ($categories as $cat) {
									$video->set_term('video_category', $cat);
								}
							}

							// actor
							$video->set_term('video_actor', $item->username, 'pornme', $item->user_id);

							$results['created'] += 1;
							$results['total'] += 1;
							$results['last_activity'] = date("d.m.Y H:i:s");

							// update video item as imported
							$wpdb->update(
								$database->table_videos,
								array(
									'imported' => 1
								),
								array(
									'video_id' => $item->video_id
								)
							);

							// update cron table (processing)
							$wpdb->update(
								AT_CRON_TABLE,
								array(
									'processing' => 0,
								),
								array(
									'id' => $id
								)
							);
						}
					} else {
						// video already exist
						$results['skipped'] += 1;
						$results['total'] += 1;

						// update video item as imported
						$wpdb->update(
							$database->table_videos,
							array(
								'imported' => 1
							),
							array(
								'video_id' => $item->video_id
							)
						);
					}
				}
			}

			$wpdb->update(
				AT_CRON_TABLE,
				array(
					'processing' => 0,
					'last_activity' => date("Y-m-d H:i:s")
				),
				array(
					'object_id' => $cron->object_id
				)
			);
		}

		at_error_log(print_r($results,true));

		at_error_log('Stoped cronjob (PornMe, ' . $id . ')');

		at_write_api_log('pornme', $cron->name, 'Total: ' . $results['total'] . ', Imported: ' . $results['created'] . ' Skipped: ' . $results['skipped']);

		echo json_encode($results);
	}
}