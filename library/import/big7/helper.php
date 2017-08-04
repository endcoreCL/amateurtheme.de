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
        if (strpos($page, 'at_import_mydirtyhobby') === false) return;

        wp_enqueue_script('at-mydirtyhobby', get_template_directory_uri() . '/library/import/_assets/js/big7.js');
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