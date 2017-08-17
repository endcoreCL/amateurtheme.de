<?php

/**
 * Created by PhpStorm.
 * User: christianlang
 * Date: 03.08.17
 * Time: 11:17
 */
class AT_Import_Video {
    var $video_id = false;
    var $post_id = false;
    var $unique = true;

    public function __construct($id) {
        $this->unique = $this->unique($id);
        $this->video_id = $id;
    }

    public function unique($id) {
        $unique = at_import_check_if_video_exists($id);

        if($unique) {
            return true;
        }

        return false;
    }

    public function insert($title, $description) {
        if(!$title) return false;

        $args = array(
            'post_title' => $title,
            'post_status' => (get_option('at_mdh_post_status') ? get_option('at_mdh_post_status') : 'publish'),
            'post_type' => 'video',
            'post_content' => $description
        );

        if (get_option('at_mdh_video_description') == '1') {
            $args['post_content'] = $description;
        }

        $this->post_id = wp_insert_post($args);

        return $this->post_id;

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

    public function set_term($taxonomy, $value, $source = 0, $id = 0) {
        $terms = wp_set_object_terms($this->post_id, $value, $taxonomy, true);

        if($taxonomy == 'video_actor') {
            if ($terms) {
                foreach ($terms as $term) {
                    $post_id = $taxonomy . '_' . $term;
                    $actor_id = get_field('actor_id', $post_id);

                    if (!$actor_id) {
                        update_field('actor_source', $source, $post_id);
                        update_field('actor_id', $id, $post_id);
                    }
                }
            }
        }
    }
}