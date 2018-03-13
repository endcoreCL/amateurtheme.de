<?php
/**
 * Loading big7 category cronjob functions
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if ( ! function_exists( 'at_import_big7_category_cronjob_initiate' ) ) {
	/**
	 * at_import_big7_category_cronjob_initiate function
	 *
	 */
	add_action('at_import_cronjob_edit', 'at_import_big7_category_cronjob_initiate', 10, 3);
	function at_import_big7_category_cronjob_initiate($id, $field, $value) {
		if ($field == 'scrape') {
			global $wpdb;

			$cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

			if ($cron) {
				if ($cron->network == 'big7' && $cron->type == 'category') {
					if ($value == '1') {
						if (!wp_next_scheduled('at_import_big7_scrape_category_videos_cronjob', array($id))) {
							wp_schedule_event(time(), 'daily', 'at_import_big7_scrape_category_videos_cronjob', array($id));
						}
					} else {
						wp_clear_scheduled_hook('at_import_big7_scrape_category_videos_cronjob', array($id));
					}
				}
			}
		}

		if ($field == 'import') {
			global $wpdb;

			$cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

			if ($cron) {
				if ($cron->network == 'big7' && $cron->type == 'category') {
					if ($value == '1') {
						if (!wp_next_scheduled('at_import_big7_import_videos_cronjob', array($id))) {
							wp_schedule_event(time(), '30min', 'at_import_big7_import_videos_cronjob', array($id));
						}
					} else {
						wp_clear_scheduled_hook('at_import_big7_import_videos_cronjob', array($id));
					}
				}
			}
		}
	}
}

if ( ! function_exists( 'at_import_big7_import_category_cronjob' ) ) {
	/**
	 * at_import_big7_import_videos_cronjob function
	 *
	 */
	add_action('at_import_big7_import_videos_cronjob', 'at_import_big7_import_videos_cronjob');
	function at_import_big7_import_videos_cronjob($id) {
		set_time_limit(120); // try to set time limit to 120 seconds

		global $wpdb;
		$cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

		if(!$cron) {
			wp_clear_scheduled_hook('at_import_big7_import_videos_cronjob', array($id));
			at_error_log('Cron ' . $id . ' deleted');
			exit;
		}

		$results = array('created' => 0, 'skipped' => 0, 'total' => 0, 'last_updated' => '');

		at_error_log('Started cronjob (BIG7, ' . $id . ')');

		if ($cron) {
			$import = new AT_Import_Big7_Crawler();
			$videos = $import->getVideos($cron->object_id);

			if ($videos) {
				$actor = $cron->name;

				foreach ($videos as $item) {
					$video_id = $item->video_id;

					$video = new AT_Import_Video($video_id);

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

						$title = (get_option('at_big7_fsk18') == 1 ? $item->title : $item->title_sc);
						$description = (get_option('at_big7_fsk18') == 1 ? $item->description : $item->description_sc);

						if(get_option('at_big7_video_description') != '1') {
							$description = '';
						}

						$post_id = $video->insert($title, $description);

						if ($post_id) {
							// fields
							$fields = at_import_big7_prepare_video_fields($item);
							if ($fields) {
								$video->set_fields($fields);
							}

							// thumbnail
							if ($item->preview) {
								$video->set_thumbnail($item->preview);
							}

							// category
							$categories = explode(',', $item->categories);
							if (is_array($categories)) {
								foreach ($categories as $cat) {
									if ($cat) {
										$video->set_term('video_category', $cat);
									}
								}
							}

							// actor
							$video->set_term('video_actor', $actor, 'big7', $cron->object_id);

							$results['created'] += 1;
							$results['total'] += 1;
							$results['last_activity'] = date("d.m.Y H:i:s");

							// update cron table (processing)
							$wpdb->query('UPDATE ' . AT_CRON_TABLE . ' SET processing = 0, created = created+1, last_activity = "' . date("Y-m-d H:i:s") . '" WHERE id = "' . $id . '"');
						}
					} else {
						// video already exist
						error_log($item->title);
						$results['skipped'] += 1;
						$results['total'] += 1;
					}
				}
			}

			/**
			 * Update Actor if timestamp is expired
			 */
			$actor_need_update = $wpdb->get_var('SELECT term_id FROM cp_termmeta WHERE meta_key = "actor_id" AND meta_value = "' . $cron->object_id . '" AND (SELECT term_id FROM cp_termmeta WHERE meta_key="actor_last_updated" AND meta_value < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY)) LIMIT 0,1)');
			if($actor_need_update) {
				// get actor data
				$actor = $import->getAmateur($cron->object_id);

				at_error_log(print_r($actor, true));

				if($actor) {
					$post_id = 'video_actor_' . $actor_need_update;

					// image
					$actor_image = get_field( 'actor_image', $post_id );
					if ( ! $actor_image ) {
						if ( $image = ($actor->image_large ? $actor->image_large : '' ) ) {
							$att_id = at_attach_external_image( $image, null, false, $actor->username . '-preview', array( 'post_title' =>  $actor->username ) );
							if ( $att_id ) {
								update_field( 'actor_image', $att_id, $post_id );
							}
						}
					}

					// gender
					if ( $gender = $actor->gender ) {
						if ( $gender == 'ts' ) {
							$gender_decoded = __( 'Transexuell', 'amateurtheme' );
						} else if ( $gender == 'w' ) {
							$gender_decoded = __( 'Weiblich', 'amateurtheme' );
						} else {
							$gender_decoded = __( 'Männlich', 'amateurtheme' );
						}

						update_field( 'actor_gender', $gender_decoded, $post_id );
					}

					// zipcode
					if ( $zipcode = $actor->zipcode ) {
						update_field( 'actor_zipcode', $zipcode, $post_id );
					}

					// city
					if ( $city = $actor->city ) {
						update_field( 'actor_city', $city, $post_id );
					}

					// country
					if ( $country = $actor->country ) {
						update_field( 'actor_country', $country, $post_id );
					}

					// eyecolor
					if ( $eyecolor = $actor->eyecolor ) {
						update_field( 'actor_eyecolor', $eyecolor, $post_id );
					}

					// haircolor
					if ( $haircolor = $actor->haircolor ) {
						update_field( 'actor_haircolor', $haircolor, $post_id );
					}

					// body
					if ( $body = $actor->body ) {
						update_field( 'actor_bodytype', $body, $post_id );
					}

					// sex
					if ( $sex = $actor->sex ) {
						update_field( 'actor_sex_orientation', $sex, $post_id );
					}

					// weight
					if ( $weight = $actor->weight ) {
						update_field( 'actor_weight', $weight, $post_id );
					}

					// shaved
					if ( $shaved = $actor->shaved ) {
						update_field( 'actor_shaved', __('Ja', 'amateurtheme'), $post_id );
					}

					// link
					if ( $link = $actor->link ) {
						update_field( 'actor_profile_url', $link, $post_id );
					}

					// aboutme
					if ( $aboutme = (get_option('at_big7_fsk18') == 1 ? $actor->aboutme : $actor->aboutme_sc) ) {
						wp_update_term($actor_need_update, 'video_actor', array(
							'description' => $aboutme,
						));
					}

					update_field( 'actor_last_updated', time(), $post_id );
				}
			}

			$wpdb->update(
				AT_CRON_TABLE,
				array(
					'processing' => 0,
					'last_activity' => date("Y-m-d H:i:s"),
				),
				array(
					'object_id' => $cron->object_id
				)
			);
		}

		at_error_log(print_r($results,true));

		at_error_log('Stoped cronjob (BIG7, ' . $id . ')');

		at_write_api_log('big7', $cron->name, 'Total: ' . $results['total'] . ', Imported: ' . $results['created'] . ' Skipped: ' . $results['skipped']);

		echo json_encode($results);
	}
}