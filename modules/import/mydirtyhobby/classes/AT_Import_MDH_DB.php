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
}