<?php
/**
 * Created by PhpStorm.
 * User: christianlang
 * Date: 17.01.17
 * Time: 11:12
 */
class AT_Video
{
    public function __construct($id) {
        $this->id = $id;
    }

    public function title() {
        return get_the_title($this->id);
    }

    public function permalink() {
        return get_permalink($this->id);
    }

    public function external_url() {
        $external_url = $this->field('url');

        if($external_url) {
            return $external_url;
        }

        return false;
    }

    public function thumbnail($size = 'full', $args = array('class' => 'img-fluid')) {
        $thumbnail = get_the_post_thumbnail($this->id, $size, $args);

        if($thumbnail) {
            return $thumbnail;
        }

        return false;
    }

    public function duration() {
        $duration = $this->field('duration');

        if($duration != '-') {
            $time = new DateTime($duration);
            if($time) {
                return $time->format('i:s');
            }
        }

        return false;
    }

    public function views() {
        $views = $this->field('views');

        if($views != '-') {
            return $views;
        }

        return 0;
    }

    public function rating() {
        $rating = $this->field('rating');

        if($rating != '-') {
            return $rating;
        }

        return 0;
    }

    public function field($name) {
        $field = get_field('video_' . $name, $this->id);

        if($field) {
            return $field;
        }

        return '-';
    }
}