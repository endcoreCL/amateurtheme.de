<?php
/**
 * Loading big7 amateur cronjob functions
 *
 * @author        Christian Lang
 * @version        1.0
 * @category    helper
 */

if ( ! function_exists( 'at_import_big7_amateur_cronjob_initiate' ) ) {
	/**
	 * at_import_big7_amateur_cronjob_initiate function
	 *
	 */
	add_action( 'at_import_cronjob_edit', 'at_import_big7_amateur_cronjob_initiate', 10, 3 );
	function at_import_big7_amateur_cronjob_initiate( $id, $field, $value )
	{
		if ( $field == 'import' ) {
			global $wpdb;

			$cron = $wpdb->get_row( 'SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id );

			if ( $cron ) {
				if ( $cron->network == 'big7' ) {
					if ( $value == '1' ) {
						if ( ! wp_next_scheduled( 'at_import_big7_import_videos_cronjob', array( $id ) ) ) {
							wp_schedule_event( time(), '30min', 'at_import_big7_import_videos_cronjob', array( $id ) );
						}
					} else {
						wp_clear_scheduled_hook( 'at_import_big7_import_videos_cronjob', array( $id ) );
					}
				}
			}
		}
	}
}

if ( ! function_exists( 'at_import_big7_amateur_cronjob_delete' ) ) {
	/**
	 * at_import_big7_amateur_cronjob_delete function
	 *
	 */
	add_action( 'at_import_cronjob_delete', 'at_import_big7_amateur_cronjob_delete', 10, 1 );
	function at_import_big7_amateur_cronjob_delete( $id )
	{
		global $wpdb;

		$cron = $wpdb->get_row( 'SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id );

		if ( $cron ) {
			if ( $cron->network == 'big7' ) {
				wp_clear_scheduled_hook( 'at_import_big7_import_videos_cronjob', array( $id ) );
			}
		}
	}
}

if ( ! function_exists( 'at_import_big7_import_videos_cronjob' ) ) {
	/**
	 * at_import_big7_import_videos_cronjob function
	 *
	 */
	add_action( 'at_import_big7_import_videos_cronjob', 'at_import_big7_import_videos_cronjob' );
	function at_import_big7_import_videos_cronjob( $id )
	{
		set_time_limit( 360 ); // try to set time limit to 360 seconds

		global $wpdb;
		$cron = $wpdb->get_row( 'SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id );

		if ( ! $cron ) {
			wp_clear_scheduled_hook( 'at_import_big7_import_videos_cronjob', array( $id ) );
			at_error_log( 'Cron ' . $id . ' deleted' );
			exit;
		}

		$results = array( 'created' => 0, 'skipped' => 0, 'total' => 0, 'last_updated' => '', 'last_pos' => $cron->last_pos );

		at_error_log( 'Started cronjob (BIG7, ' . $id . ')' );

		if ( $cron ) {
			$import = new AT_Import_Big7_Crawler();

			if ( $cron->type == 'user' ) {
				$videos = $import->getVideos( $cron->object_id );
			} else {
				$import_limit = apply_filters( 'at_import_category_limit', 200 );
				$import_limit = 1;

				// check max videos
				$total_videos_query = $import->getVideosByCategory( $cron->name, 0, 999999 );
				if ( $total_videos_query ) {
					$total_videos = count( $total_videos_query );

					if ( $cron->last_pos >= $total_videos ) {
						$cron->last_pos      = 0;
						$results['last_pos'] = 0;
					}
				}

				$videos = $import->getVideosByCategory( $cron->name, ( $cron->last_pos ? $cron->last_pos : 0 ), $import_limit );
			}

			if ( $videos ) {
				$updated_actors = array();

				foreach ( $videos as $item ) {
					$video_id = $item->video_id;

					$video = new AT_Import_Video( $video_id );

					if ( $video->unique ) {
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

						$title       = ( get_option( 'at_big7_fsk18' ) == 1 ? $item->title : $item->title_sc );
						$description = ( get_option( 'at_big7_fsk18' ) == 1 ? $item->description : $item->description_sc );

						if ( get_option( 'at_big7_video_description' ) != '1' ) {
							$description = '';
						}

						$post_id = $video->insert( $title, $description );

						if ( $post_id ) {
							// fields
							$fields = at_import_big7_prepare_video_fields( $item );
							if ( $fields ) {
								$video->set_fields( $fields );
							}

							// thumbnail
							if ( $item->preview ) {
								$video->set_thumbnail( $item->preview );
							}

							// category
							$categories = explode( ',', $item->categories );
							if ( is_array( $categories ) ) {
								foreach ( $categories as $cat ) {
									if ( $cat ) {
										$video->set_term( 'video_category', $cat );
									}
								}
							}

							// actor
							$actor = $import->getAmateurName( $item->uid );
							if ( $actor ) {
								$video->set_term( 'video_actor', $actor, 'big7', $item->uid );
								// update Actor if timestamp is expired
								if ( ! in_array( $item->uid, $updated_actors ) ) {
									at_import_big7_update_actor( $item->uid );
									$updated_actors[] = $item->uid;
								}
							}

							$results['created']       += 1;
							$results['total']         += 1;
							$results['last_pos']      += 1;
							$results['last_activity'] = date( "d.m.Y H:i:s" );

							// update cron table (processing)
							$wpdb->query( 'UPDATE ' . AT_CRON_TABLE . ' SET processing = 0, created = created+1, last_activity = "' . date( "Y-m-d H:i:s" ) . '" WHERE id = "' . $id . '"' );
						}
					} else {
						// video already exist
						$results['skipped']  += 1;
						$results['total']    += 1;
						$results['last_pos'] += 1;
					}
				}
			}

			$wpdb->update(
				AT_CRON_TABLE,
				array(
					'processing'    => 0,
					'last_activity' => date( "Y-m-d H:i:s" ),
					'last_pos'      => $results['last_pos']
				),
				array(
					'object_id' => $cron->object_id
				)
			);
		}

		at_error_log( print_r( $results, true ) );

		at_error_log( 'Stoped cronjob (BIG7, ' . $id . ')' );

		at_write_api_log( 'big7', $cron->name, 'Total: ' . $results['total'] . ', Imported: ' . $results['created'] . ' Skipped: ' . $results['skipped'] );

		echo json_encode( $results );
	}
}