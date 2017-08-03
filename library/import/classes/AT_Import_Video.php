<?php

/**
 * Created by PhpStorm.
 * User: christianlang
 * Date: 03.08.17
 * Time: 11:17
 */
class AT_Import_Video {
    var $video_id = '';
    var $post_id = '';

    public function __construct($id) {
        if(!$this->unique($id)) {
            return false;
        }

        $this->video_id = $id;

        return true;
    }

    public function unique($id) {
        $unique = at_import_mdh_check_if_video_exists($id);

        if($unique) {
            return true;
        }

        return false;
    }

    public function insert() {
        global $wpdb;

        $database = new AT_Import_MDH_DB();
        $video = $wpdb->get_row('SELECT * FROM ' . $database->table_videos . ' WHERE video_id = ' .$this->video_id);

        if($video) {
            $args = array(
                'post_title' => $video->title,
                'post_status' => (get_option('at_mdh_post_status') ? get_option('at_mdh_post_status') : 'publish'),
                'post_type' => 'video',
            );

            if(get_option('at_mdh_video_description') == '1') {
                $args['post_content'] = $video->description;
            }

            $this->post_id = wp_insert_post($args);

            return $this->post_id;
        }

       return false;
    }

    public function set_fields($args = array()) {
        if($args) {
            foreach($args as $k => $v) {
                update_post_meta($this->post_id, 'video_' . $k, $v);
            }
        }

        return false;
    }

    public function set_thumbnail($url) {
        $att_id = at_attach_external_image($url, $this->post_id, true, $this->video_id.'-preview', array('post_title' => get_the_title($this->post_id)));

        if($att_id) {
            return true;
        }

        return false;
    }

    public function set_term($taxonomy, $value) {
        wp_set_object_terms($this->post_id, $value, $taxonomy, true);
    }
}