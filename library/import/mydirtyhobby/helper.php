<?php
/**
 * Loading mydirtyhobby import helper functions
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if ( ! function_exists( 'at_import_mdh_scripts' ) ) {
    /**
     * at_import_mdh_scripts function
     *
     */
    add_action('admin_enqueue_scripts', 'at_import_mdh_scripts');
    function at_import_mdh_scripts($page) {
        if (strpos($page, 'at_import_mydirtyhobby') === false) return;

        wp_enqueue_script('at-mydirtyhobby', get_template_directory_uri() . '/library/import/assets/js/mydirtyhobby.js');
    }
}

if ( ! function_exists( 'at_import_mdh_get_video_count' ) ) {
    /**
     * at_import_mdh_get_video_count
     *
     * @param $source_id
     * @param bool $imported
     * @return string
     */
    function at_import_mdh_get_video_count($source_id, $imported = false) {
        global $wpdb;

        $database = new AT_Import_MDH_DB();

        if ($imported) {
            $result = $wpdb->get_row('SELECT COUNT(source_id) as count FROM ' . $database->table_videos . ' WHERE source_id = "' . $source_id . '" AND imported = 1');
        } else {
            $result = $wpdb->get_row('SELECT COUNT(source_id) as count FROM ' . $database->table_videos . ' WHERE source_id = "' . $source_id . '"');
        }

        if ($result) {
            return $result->count;
        }

        return '0';
    }
}

if ( ! function_exists( 'at_import_mdh_check_if_video_exists' ) ) {
    /**
     * at_import_mdh_check_if_video_exists
     *
     * @param $video_id
     * @return bool
     */
    function at_import_mdh_check_if_video_exists($video_id) {
        global $wpdb;

        $database = new AT_Import_MDH_DB();

        $unique_user_video = $wpdb->get_var(
            $wpdb->prepare("
			SELECT count(id)
			FROM $database->table_videos
			WHERE video_id = '%s'
			AND imported = '1'",
                $video_id
            )
        );

        if ($unique_user_video != '0') {
            return false;
        }

        $unique_post = $wpdb->get_var(
            $wpdb->prepare("
			SELECT count(id)
			FROM $wpdb->posts wpo, $wpdb->postmeta wpm
			WHERE wpo.ID = wpm.post_id
			AND wpm.meta_key = 'video_unique_id'
			AND wpm.meta_value = '%s'",
                $video_id
            )
        );

        if ($unique_post != '0') {
            return false;
        }

        return true;
    }
}

if ( ! function_exists( 'at_import_mdh_prepare_video_fields' ) ) {
    /**
     * at_import_mdh_prepare_video_fields
     *
     * @param $video_id
     * @return array|bool
     */
    function at_import_mdh_prepare_video_fields($video_id) {
        global $wpdb;
        $database = new AT_Import_MDH_DB();
        $video = $wpdb->get_row('SELECT * FROM ' . $database->table_videos . ' WHERE video_id = ' . $video_id);
        if ($video) {
            // format duration
            $raw_duration = $video->duration;
            $duration = gmdate("H:i:s", str_replace('.', '', $raw_duration));

            // format date
            $raw_date = $video->date;
            $date = date('Ymd', strtotime($raw_date));

            // format rating
            $raw_rating = $video->rating;
            $rating = $rating = round(($raw_rating / 2) * 2) / 2;

            $fields = array(
                'duration' => $duration,
                'views' => 0,
                'date' => $date,
                'rating' => $rating,
                'rating_count' => $video->rating_count,
                'url' => $video->link,
                'source' => 'mdh',
                'language' => $video->language,
                'unique_id' => $video_id
            );

            return $fields;
        }

        return false;
    }
}