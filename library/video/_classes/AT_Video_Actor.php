<?php

/**
 * Created by PhpStorm.
 * User: christianlang
 * Date: 17.08.17
 * Time: 10:41
 */
class AT_Video_Actor
{
    public function __construct($id) {
        $this->id = $id;
        $this->term = get_term_by('id', $id, 'video_actor');
    }

    public function image() {
        $actor_image = get_field('actor_image', $this->term);

        if($actor_image) {
            return $actor_image;
        }

        return false;
    }

    public function url() {
        $actor_profile_url = get_field('actor_profile_url', $this->term);

        if($actor_profile_url) {
            return $actor_profile_url;
        }

        return false;
    }

    public function field($name) {
        $field = get_field('actor_' . $name, $this->term);

        if($field) {
            return $field;
        }

        return '-';
    }
}