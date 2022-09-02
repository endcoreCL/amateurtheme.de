<?php
/**
 * Loading big7 import helper functions
 *
 * @author        Christian Lang
 * @version        1.0
 * @category    helper
 */

if ( ! function_exists( 'at_import_big7_scripts' ) ) {
	/**
	 * at_import_big7_scripts function
	 *
	 */
	add_action( 'admin_enqueue_scripts', 'at_import_big7_scripts' );
	function at_import_big7_scripts( $page )
	{
		if ( strpos( $page, 'at_import_big7' ) === false ) {
			return;
		}

		wp_enqueue_script( 'at-big7', get_template_directory_uri() . '/modules/import/_assets/js/big7.js' );
	}
}

if ( ! function_exists( 'at_import_big7_prepare_video_fields' ) ) {
	/**
	 * at_import_big7_prepare_video_fields
	 *
	 * @param $video_id
	 *
	 * @return array|bool
	 */
	function at_import_big7_prepare_video_fields( $data )
	{
		if ( $data ) {
			// format duration
			$raw_duration = preg_replace( '/\D/', '', $data->duration );
			$duration     = gmdate( "H:i:s", $raw_duration );

			// format date
			$raw_date = $data->date;
			$date     = date( 'Ymd', strtotime( $raw_date ) );

			// format rating
			$raw_rating = $data->rating / 10;
			$rating     = $rating = round( ( $raw_rating / 2 ) * 2 ) / 2;

			$fields = array(
				'duration'     => $duration,
				'views'        => 0,
				'date'         => $date,
				'rating'       => $rating,
				'rating_count' => $data->rating_count,
				'url'          => $data->link,
				'source'       => 'big7',
				'language'     => 'de',
				'preview_webm' => $data->preview_webm,
				'preview_mp4'  => $data->preview_mp4,
				'unique_id'    => $data->video_id
			);

			return $fields;
		}

		return false;
	}
}

if ( ! function_exists( 'at_import_big7_get_video_count' ) ) {
	/**
	 * at_import_big7_get_video_count
	 *
	 * @param $source_id
	 * @param bool $imported
	 *
	 * @return string
	 */
	function at_import_big7_get_video_count( $source_id )
	{
		global $wpdb;

		$database = new AT_Import_Big7_DB();

		$videos = $wpdb->get_var( 'SELECT COUNT(id) as count FROM ' . $database->table_videos . ' WHERE uid = ' . $source_id );

		if ( $videos ) {
			return $videos;
		}

		return '0';
	}
}

if ( ! function_exists( 'at_import_big7_get_imported_video_count' ) ) {
	/**
	 * at_import_big7_get_imported_video_count
	 *
	 * @param $source_id
	 * @param bool $imported
	 *
	 * @return string
	 */
	function at_import_big7_get_imported_video_count( $source_id )
	{
		global $wpdb;

		$term = $wpdb->get_var( 'SELECT term_id FROM ' . $wpdb->termmeta . ' WHERE meta_value = "' . $source_id . '"' );

		if ( $term ) {
			$item = get_term_by( 'id', $term, 'video_actor' );
			if ( $item ) {
				return $item->count;
			}
		}

		return '0';
	}
}

if ( ! function_exists( 'at_import_big7_get_category_video_count' ) ) {
	/**
	 * at_import_big7_get_video_category_count
	 *
	 * @param $source_id
	 * @param bool $imported
	 *
	 * @return string
	 */
	function at_import_big7_get_category_video_count( $category )
	{
		global $wpdb;

		$database = new AT_Import_Big7_DB();

		$videos = $wpdb->get_var( 'SELECT COUNT(id) as count FROM ' . $database->table_videos . ' WHERE categories LIKE "%' . $category . '%"' );

		if ( $videos ) {
			return $videos;
		}

		return '0';
	}
}

if ( ! function_exists( 'at_import_big7_get_category_imported_video_count' ) ) {
	/**
	 * at_import_big7_get_category_imported_video_count
	 *
	 * @param $source_id
	 * @param bool $imported
	 *
	 * @return string
	 */
	function at_import_big7_get_category_imported_video_count( $category )
	{
		global $wpdb;

		$term = get_term_by( 'name', $category, 'video_actor' );

		if ( $term ) {
			return $term->count;
		}

		return '0';
	}
}

if ( ! function_exists( 'at_import_big7_get_categories' ) ) {
	/**
	 * at_import_big7_get_categories
	 *
	 * @return array|bool
	 */
	function at_import_big7_get_categories()
	{
		$Crawler    = new AT_Import_Big7_Crawler();
		$categories = $Crawler->getCategories();
		$fsk18      = get_option( 'at_big7_fsk18' );
		$results    = [];

		if ( $categories ) {
			foreach ( $categories as $term ) {
				$results[ $term['id'] ] = $fsk18 ? $term['name'] : $term['name_softcore'];
			}
		}

		return $results;
	}
}

if ( ! function_exists( 'at_import_json_process' ) ) {
	/**
	 * at_import_json_process
	 *
	 * @param $item
	 */
	function at_import_json_process( $item )
	{
		print_r( $item );
	}
}

function at_import_big7_update_actor( $actor_id )
{
	global $wpdb;

	$actor_need_update = $wpdb->get_var( 'SELECT term_id FROM cp_termmeta WHERE meta_key = "actor_id" AND meta_value = "' . $actor_id . '" AND (SELECT term_id FROM cp_termmeta WHERE meta_key="actor_last_updated" AND meta_value < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY)) LIMIT 0,1)' );

	if ( $actor_need_update ) {
		// get actor data
		$import = new AT_Import_Big7_Crawler();
		$actor  = $import->getAmateur( $actor_id );

		if ( $actor ) {
			$post_id = 'video_actor_' . $actor_need_update;

			// image
			$actor_image = get_field( 'actor_image', $post_id );
			if ( ! $actor_image ) {
				if ( $image = ( $actor->image_large ? $actor->image_large : '' ) ) {
					$att_id = at_attach_external_image( $image, null, false, $actor->username . '-preview', array( 'post_title' => $actor->username ) );
					if ( $att_id ) {
						update_field( 'actor_image', $att_id, $post_id );
					}
				}
			}

			// gender
			if ( $gender = $actor->gender ) {
				if ( $gender == 'ts' ) {
					$gender_decoded = __( 'Transexuell', 'amateurtheme' );
				} elseif ( $gender == 'w' ) {
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
				update_field( 'actor_shaved', __( 'Ja', 'amateurtheme' ), $post_id );
			}

			// link
			if ( $link = $actor->link ) {
				update_field( 'actor_profile_url', $link, $post_id );
			}

			// aboutme
			if ( $aboutme = ( get_option( 'at_big7_fsk18' ) == 1 ? $actor->aboutme : $actor->aboutme_sc ) ) {
				wp_update_term( $actor_need_update, 'video_actor', array(
					'description' => $aboutme,
				) );
			}

			update_field( 'actor_last_updated', time(), $post_id );

			at_error_log( 'Updated Actor: ' . $actor->username . ' (' . $actor_need_update . ')' );
		}
	}
}

/**
 * Add promo and campaign param for all big7 urls
 *
 * @param $value
 * @param $post_id
 * @param $field
 *
 * @return string
 */
add_filter( 'acf/load_value/name=video_url', 'xcore_big7_campaign_urls', 10, 3 );
function xcore_big7_campaign_urls( $value, $post_id, $field )
{
	if ( get_field( 'video_source', $post_id ) == 'big7' ) {
		$campaign = get_option( 'at_big7_video_campaign' );
		if ( $campaign ) {
			$value .= '&wms=' . $campaign;
		}
	}

	return $value;
}