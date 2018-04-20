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