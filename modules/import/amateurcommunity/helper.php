<?php
/**
 * Loading ac import helper functions
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if ( ! function_exists( 'at_import_ac_scripts' ) ) {
    /**
     * at_import_ac_scripts function
     *
     */
    add_action('admin_enqueue_scripts', 'at_import_ac_scripts');
    function at_import_ac_scripts($page) {
        if (strpos($page, 'at_import_ac') === false) return;

        wp_enqueue_script('at-ac', get_template_directory_uri() . '/modules/import/_assets/js/ac.js');
    }
}

if ( ! function_exists( 'at_import_ac_prepare_video_fields' ) ) {
	/**
	 * at_import_ac_prepare_video_fields
	 *
	 * @param $video_id
	 * @return array|bool
	 */
	function at_import_ac_prepare_video_fields($media_id) {
		global $wpdb;
		$database = new AT_Import_AC_DB();
		$video = $wpdb->get_row('SELECT * FROM ' . $database->table_media . ' WHERE media_id = ' . $media_id);

		if ($video) {
			// format date
			$raw_date = $video->date;
			$date = date('Ymd', strtotime($raw_date));

			$fields = array(
				'duration' => '',
				'views' => 0,
				'date' => $date,
				'rating' => $video->rating,
				'rating_count' => $video->votes,
				'url' => $video->url,
				'source' => 'ac',
				'language' => 'de',
				'unique_id' => $video->media_id
			);

			return $fields;
		}

		return false;
	}
}

if ( ! function_exists( 'at_import_ac_get_video_count' ) ) {
	/**
	 * at_import_ac_get_video_count
	 *
	 * @param $source_id
	 * @param bool $imported
	 * @return string
	 */
	function at_import_ac_get_video_count($source_id) {
		global $wpdb;

		$database = new AT_Import_AC_DB();

		$videos = $wpdb->get_var('SELECT COUNT(id) as count FROM ' . $database->table_media . ' WHERE uid = ' . $source_id);

		if($videos) {
			return $videos;
		}

		return '0';
	}
}

if ( ! function_exists( 'at_import_ac_get_imported_video_count' ) ) {
	/**
	 * at_import_ac_get_imported_video_count
	 *
	 * @param $source_id
	 * @param bool $imported
	 * @return string
	 */
	function at_import_ac_get_imported_video_count($source_id) {
		global $wpdb;

		$database = new AT_Import_AC_DB();

		$videos = $wpdb->get_var('SELECT COUNT(id) as count FROM ' . $database->table_media . ' WHERE uid = ' . $source_id . ' AND imported = 1');

		if($videos) {
			return $videos;
		}

		return '0';
	}
}

if( ! function_exists( 'at_import_ac_untag_video_as_imported' ) ) {
	/**
	 * at_import_ac_untag_video_as_imported
	 */
	add_action( 'before_delete_post', 'at_import_ac_untag_video_as_imported' );
	function at_import_ac_untag_video_as_imported( $post_id ) {
		global $wpdb;

		global $post_type;
		if ( $post_type != 'video' ) {
			return;
		}

		$video_source = get_field( 'video_source', $post_id );
		if ( $video_source != 'ac' ) {
			return;
		}

		$database = new AT_Import_AC_DB();
		$video_id = get_post_meta( $post_id, 'video_unique_id', true );

		if ( $video_id ) {
			$wpdb->update(
				$database->table_media,
				array(
					'imported' => '0'
				),
				array(
					'media_id' => $video_id
				)
			);
		}
	}
}

function at_import_ac_update_actor($actor_id) {
	global $wpdb;

	$actor_need_update = $wpdb->get_var('SELECT term_id FROM cp_termmeta WHERE meta_key = "actor_id" AND meta_value = "' . $actor_id . '" AND (SELECT term_id FROM cp_termmeta WHERE meta_key="actor_last_updated" AND meta_value < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY)) LIMIT 0,1)');


	if($actor_need_update) {
		// get actor data
		$import = new AT_Import_AC_Crawler();
		$database = new AT_Import_AC_DB();

		$actor = $wpdb->get_row('SELECT * FROM ' . $database->table_amateurs . ' WHERE uid = ' . $actor_id);
		if($actor) {
			$post_id = 'video_actor_' . $actor_need_update;

			// image
			$actor_image = get_field( 'actor_image', $post_id );
			if ( ! $actor_image ) {
				$image = (get_option('at_ac_fsk18') == '1' ? $actor->image_large : $actor->image_large_sc);
				if ( $image ) {
					$att_id = at_attach_external_image( $image, null, false, $actor->nickname . '-preview', array( 'post_title' =>  $actor->nickname ) );
					if ( $att_id ) {
						update_field( 'actor_image', $att_id, $post_id );
					}
				}
			}

			// gender
			if ( $gender = $actor->gender ) {
				if ( $gender == 'weiblich' ) {
					$gender_decoded = __( 'Weiblich', 'amateurtheme' );
				} else {
					$gender_decoded = __( 'Männlich', 'amateurtheme' );
				}

				update_field( 'actor_gender', $gender_decoded, $post_id );
			}

			// age
			if ( $age = $actor->age ) {
				update_field( 'actor_age', $age, $post_id );
			}

			// zipcode
			if ( $zipcode = $actor->zipArea ) {
				update_field( 'actor_zipcode', $zipcode, $post_id );
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

			// hairlength
			if ( $hairlength = $actor->hairlength ) {
				update_field( 'actor_hairlength', $hairlength, $post_id );
			}

			// body
			if ( $body = $actor->body ) {
				update_field( 'actor_bodytype', $body, $post_id );
			}

			// brasize
			if ( $brasize = $actor->brasize ) {
				update_field( 'actor_breast_size', $brasize, $post_id );
			}

			// height
			if ( $height = $actor->height ) {
				update_field( 'actor_size', $height . ' cm', $post_id );
			}

			// weight
			if ( $weight = $actor->weight ) {
				update_field( 'actor_weight', $weight . ' kg', $post_id );
			}

			// shaved
			if ( $bodyhair = $actor->bodyhair ) {
				update_field( 'actor_shaved', $bodyhair, $post_id );
			}

			// link
			if ( $link = $actor->url ) {
				update_field( 'actor_profile_url', $link, $post_id );
			}

			// lookingfor
			if ( $lookingfor = $actor->lookingfor ) {
				update_field( 'actor_search_for', implode(', ', json_decode($lookingfor)), $post_id );
			}

			// preferences
			if ( $preferences = $actor->preferences ) {
				update_field( 'actor_preferences', implode(', ', json_decode($preferences)), $post_id );
			}

			// marital
			if ( $marital = $actor->marital ) {
				update_field( 'actor_relationship_status', $marital, $post_id );
			}

			// aboutme
			if ( $aboutme = (get_option('at_ac_fsk18') == 1 ? $actor->description : $actor->description_sc) ) {
				wp_update_term($actor_need_update, 'video_actor', array(
					'description' => $aboutme,
				));
			}

			update_field( 'actor_last_updated', time(), $post_id );

			at_error_log('Updated Actor: ' . $actor->nickname . ' (' . $actor_need_update . ')');
		}
	}
}