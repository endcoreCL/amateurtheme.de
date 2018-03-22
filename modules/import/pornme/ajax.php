<?php
/**
 * Loading pornme ajax functions
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if ( ! function_exists( 'at_import_pornme_get_videos' ) ) {
	/**
	 * at_import_pornme_get_videos function
	 *
	 */
	add_action('wp_ajax_at_import_pornme_get_videos', 'at_import_pornme_get_videos');
	function at_import_pornme_get_videos() {
		$u_id = (isset($_GET['u_id']) ? $_GET['u_id'] : false);

		if (!$u_id) {
			echo json_encode(array('status', 'error'));
			exit;
		}

		$import = new AT_Import_PornMe_Crawler();
		$response = $import->getAmateurVideos($u_id);

		if ($response) {
			foreach ($response as $item) {
				$item->imported = "false";

				$unique = at_import_check_if_video_exists($item->id);

				if (!$unique) {
					$item->imported = "true";
				}
			}
		}

		echo json_encode($response);
		exit;
	}
}

if ( ! function_exists( 'at_import_pornme_amateurs_select' ) ) {
	/**
	 * at_import_pornme_amateurs_select function
	 *
	 */
	add_action('wp_ajax_at_pornme_amateurs', 'at_import_pornme_amateurs_select');
	function at_import_pornme_amateurs_select() {
		global $wpdb;

		$database = new AT_Import_PornMe_DB();

		$q = (isset($_GET['q']) ? $_GET['q'] : false);

		if(!$q) {
			die();
		}

		$limit = 500;

		$output = array();

		$items = $wpdb->get_results('SELECT * FROM ' . $database->table_amateurs . ' WHERE username LIKE "%' . $q . '%" LIMIT 0,' . $limit);

		if($items) {
			foreach($items as $item) {
				$output[] = array($item->uid, $item->username);
			}
		}

		echo json_encode($output);

		die();
	}
}

if ( ! function_exists( 'at_pornme_import_video' ) ) {
	/**
	 * at_pornme_import_video function
	 *
	 */
	add_action("wp_ajax_at_pornme_import_video", "at_pornme_import_video");
	function at_pornme_import_video() {
		global $wpdb;

		$database = new AT_Import_PornMe_DB();
		$results = '';
		$video_id = $_POST['id'];
		$video_category = $_POST['video_category'];
		$video_actor = $_POST['video_actor'];
		$source_id = $_POST['source_id'];

		$video = new AT_Import_Video($video_id);

		if ($video->unique) {
			$item = $wpdb->get_row('SELECT * FROM ' . $database->table_videos . ' WHERE video_id = ' . $video_id);

			if ($item) {
				$cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE object_id = ' . $source_id);

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
					if ($video_category != '-1' && $video_category != '') {
						$video->set_term( 'video_category', $video_category );
					} else {
						$categories = json_decode( $item->tags );
						if ( $categories ) {
							foreach ( $categories as $cat ) {
								$video->set_term( 'video_category', $cat );
							}
						}
					}

					// actor
					if ($video_actor != '-1' && $video_actor != '') {
						$video->set_term('video_actor', $video_actor, 'pornme', $video_actor);
					} else {
						$video->set_term('video_actor', $cron->name, 'pornme', $cron->object_id);
					}

					// update video item as imported
					$wpdb->update(
						$database->table_videos,
						array(
							'imported' => 1
						),
						array(
							'video_id' => $video_id
						)
					);
				}

				$results = $video_id;
			}
		} else {
			// video already exist, update video as imported
			$wpdb->update(
				$database->table_videos,
				array(
					'imported' => 1
				),
				array(
					'video_id' => $video_id
				)
			);

			$results = 'error';
		}

		echo $results;

		die();
	}
}