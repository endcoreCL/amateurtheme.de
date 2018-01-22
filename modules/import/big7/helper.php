<?php
/**
 * Loading big7 import helper functions
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if ( ! function_exists( 'at_import_big7_scripts' ) ) {
    /**
     * at_import_big7_scripts function
     *
     */
    add_action('admin_enqueue_scripts', 'at_import_big7_scripts');
    function at_import_big7_scripts($page) {
        if (strpos($page, 'at_import_big7') === false) return;

        wp_enqueue_script('at-big7', get_template_directory_uri() . '/modules/import/_assets/js/big7.js');
    }
}

if ( ! function_exists( 'at_import_big7_prepare_video_fields' ) ) {
    /**
     * at_import_big7_prepare_video_fields
     *
     * @param $video_id
     * @return array|bool
     */
    function at_import_big7_prepare_video_fields($data) {
        if ($data) {
            // format duration
            $raw_duration = preg_replace('/\D/', '', $data['dauer']);
            $duration = gmdate("H:i:s", $raw_duration);

            // format date
            $raw_date = $data['veroeffentlicht'];
            $date = date('Ymd', strtotime($raw_date));

            // format rating
            $raw_rating = $data['bewertung'] / 10;
            $rating = $rating = round(($raw_rating / 2) * 2) / 2;

            $fields = array(
                'duration' => $duration,
                'views' => 0,
                'date' => $date,
                'rating' => $rating,
                'rating_count' => $data['anz_bewertung'],
                'url' => $data['direktlink'],
                'source' => 'big7',
                'language' => 'de',
                'unique_id' => md5($data['name'])
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
     * @return string
     */
    function at_import_big7_get_video_count($source_id) {
        global $wpdb;

        $term = $wpdb->get_var('SELECT term_id FROM ' . $wpdb->termmeta . ' WHERE meta_value = "' . $source_id . '"');

        if($term) {
            $item = get_term_by('id', $term, 'video_actor');
            if($item) {
                return $item->count;
            }
        }

        return '0';
    }
}

function at_import_json_process($item) {
    print_r($item);
}
