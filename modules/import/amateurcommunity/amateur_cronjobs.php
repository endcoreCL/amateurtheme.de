<?php
/**
 * Loading ac amateur cronjob functions
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if ( ! function_exists( 'at_import_ac_amateur_cronjob_initiate' ) ) {
    /**
     * at_import_ac_amateur_cronjob_initiate function
     *
     */
    add_action('at_import_cronjob_edit', 'at_import_ac_amateur_cronjob_initiate', 10, 3);
    function at_import_ac_amateur_cronjob_initiate($id, $field, $value) {
	    if ($field == 'scrape') {
		    global $wpdb;

		    $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

		    if ($cron) {
			    if ($cron->network == 'ac' && $cron->type == 'user') {
				    if ($value == '1') {
					    if (!wp_next_scheduled('at_import_ac_scrape_videos_cronjob', array($id))) {
						    wp_schedule_event(time(), 'daily', 'at_import_ac_scrape_videos_cronjob', array($id));
					    }
				    } else {
					    wp_clear_scheduled_hook('at_import_ac_scrape_videos_cronjob', array($id));
				    }
			    }
		    }
	    }

	    if ($field == 'import') {
		    global $wpdb;

		    $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

		    if ($cron) {
			    if ($cron->network == 'ac') {
				    if ($value == '1') {
					    if (!wp_next_scheduled('at_import_ac_import_videos_cronjob', array($id))) {
						    wp_schedule_event(time(), '30min', 'at_import_ac_import_videos_cronjob', array($id));
					    }
				    } else {
					    wp_clear_scheduled_hook('at_import_ac_import_videos_cronjob', array($id));
				    }
			    }
		    }
	    }
    }
}

if ( ! function_exists( 'at_import_ac_amateur_cronjob_delete' ) ) {
	/**
	 * at_import_ac_amateur_cronjob_delete function
	 *
	 */
	add_action('at_import_cronjob_delete', 'at_import_ac_amateur_cronjob_delete', 10, 1);
	function at_import_ac_amateur_cronjob_delete($id) {
		global $wpdb;

		$cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

		if ($cron) {
			if ($cron->network == 'ac') {
				wp_clear_scheduled_hook('at_import_ac_import_media_cronjob', array($id));
			}
		}
	}
}

if ( ! function_exists( 'at_import_ac_scrape_videos_cronjob' ) ) {
	/**
	 * at_import_ac_scrape_videos_cronjob function
	 *
	 */
	add_action('at_import_ac_scrape_videos_cronjob', 'at_import_ac_scrape_videos_cronjob');
	function at_import_ac_scrape_videos_cronjob($id) {
		global $wpdb;
		$cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

		at_error_log(print_r($cron, true));

		if(!$cron) {
			wp_clear_scheduled_hook('at_import_ac_scrape_videos_cronjob', array($id));
			at_error_log('Cron ' . $id . ' deleted');
			exit;
		}

		$results = array('created' => 0, 'skipped' => 0, 'total' => 0, 'last_updated' => '');

		at_error_log('Started cronjob (AC, Crawl ' . $id . ')');

		if ($cron) {
			$uid = $cron->object_id;
			$username = $cron->name;

			// initiate import
			$import = new AT_Import_AC_Crawler();

			$wpdb->update(
				AT_CRON_TABLE,
				array(
					'processing' => 1,
				),
				array(
					'id' => $id
				)
			);


			$result = $import->jsonMedias($username);

			if(isset($result[0]['galleries'])) {
				$data = json_decode($import->saveMedias($uid, $username, $result[0]['galleries']), true );

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

		at_error_log('Stoped cronjob (AC, Crawl, ' . $id . ')');

		echo json_encode($results);
	}
}

if ( ! function_exists( 'at_import_ac_import_videos_cronjob' ) ) {
	/**
	 * at_import_ac_import_videos_cronjob function
	 *
	 */
	add_action('at_import_ac_import_videos_cronjob', 'at_import_ac_import_videos_cronjob');
	function at_import_ac_import_videos_cronjob($id) {
		set_time_limit(120); // try to set time limit to 120 seconds

		global $wpdb;
		$cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

		if(!$cron) {
			wp_clear_scheduled_hook('at_import_ac_import_videos_cronjob', array($id));
			at_error_log('Cron ' . $id . ' deleted');
			exit;
		}

		$results = array('created' => 0, 'skipped' => 0, 'total' => 0, 'last_updated' => '');

		at_error_log('Started cronjob (AC, ' . $id . ')');

		if ($cron) {
			$database = new AT_Import_AC_DB();
			$videos = $wpdb->get_results('SELECT * FROM ' . $database->table_media . ' WHERE uid = "' . $cron->object_id . '" AND imported != 1 LIMIT 0,50');

			if ($videos) {
				foreach ($videos as $item) {
					$video = new AT_Import_Video($item->media_id);

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

						$title = (get_option('at_ac_fsk18') == '1' ? $item->title : $item->title_sc);
						$description = (get_option('at_ac_fsk18') == '1' ? $item->description : $item->descriptionsc);

						$post_id = $video->insert($title, $description);

						if ($post_id) {
							// fields
							$fields = at_import_ac_prepare_video_fields($item->media_id);
							if ($fields) {
								$video->set_fields($fields);
							}

							// thumbnail
							$preview = (get_option('at_ac_fsk18') == '1' ? $item->preview : $item->preview_sc);
							if ($preview) {
								$video->set_thumbnail('https://' . $preview);
							}

							// category
							$categories = json_decode($item->categories);
							if ($categories) {
								foreach ($categories as $cat) {
									$video->set_term('video_category', $cat);
								}
							}

							// actor
							$video->set_term('video_actor', $item->nickname, 'ac', $item->uid);

							$results['created'] += 1;
							$results['total'] += 1;
							$results['last_activity'] = date("d.m.Y H:i:s");

							// update video item as imported
							$wpdb->update(
								$database->table_media,
								array(
									'imported' => 1
								),
								array(
									'media_id' => $item->media_id
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
							$database->table_media,
							array(
								'imported' => 1
							),
							array(
								'media_id' => $item->media_id
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

		at_error_log('Stoped cronjob (AC, ' . $id . ')');

		at_write_api_log('ac', $cron->name, 'Total: ' . $results['total'] . ', Imported: ' . $results['created'] . ' Skipped: ' . $results['skipped']);

		echo json_encode($results);
	}
}