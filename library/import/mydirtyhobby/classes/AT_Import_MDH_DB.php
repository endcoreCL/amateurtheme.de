<?php

/**
 * Created by PhpStorm.
 * User: christianlang
 * Date: 25.07.17
 * Time: 14:12
 */
class AT_Import_MDH_DB {
    public function __construct() {
        // tables
        global $wpdb;
        $this->table_amateurs = $wpdb->prefix . 'import_mdh_amateurs';
        $this->table_videos = $wpdb->prefix . 'import_mdh_videos';
    }

    public function amateurs_dropdown() {
        global $wpdb;

        $amateurs = $wpdb->get_results('SELECT * FROM ' . $this->table_amateurs);

        $output = '';

        if($amateurs) {
            foreach($amateurs as $item) {
                $output .= '<option value="' . $item->uid . '">' . $item->username . '</option>';
            }
        }

        return $output;
    }
}