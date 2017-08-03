<?php

/**
 * Created by PhpStorm.
 * User: christianlang
 * Date: 25.07.17
 * Time: 12:08
 */
class AT_Import_MDH_Crawler {
    public $naffcode;
    public $url;

    public function __construct() {
        $this->naffcode = get_option('at_mdh_naffcode');
        $this->amateurs_url = 'https://www.mydirtyhobby.com/api/amateurs/?naff=' . $this->naffcode;
        $this->videos_url = 'https://www.mydirtyhobby.com/api/amateurvideos/?naff=' . $this->naffcode;

        // tables
        global $wpdb;
        $this->database = new AT_Import_MDH_DB();
    }

    function get($params = array()) {
        $url = $this->amateurs_url;

        if($params['type'] == 'videos') {
            $url = $this->videos_url;
        }

        if(!empty($params)) {
            $url = $url . '&'  . http_build_query($params);
        }
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($curl);

        if(curl_errno($curl)) {
            return 'Curl error: ' . curl_error($curl);
        }

        curl_close($curl);

        return json_decode($response);
    }

    function getTotal($args = array('limit' => 1)) {
        $data = $this->get($args);

        if($data) {
            return $data->total;
        }

        return false;
    }


    function crawlAmateurs($offset = 0) {
        $message = array('status' => 'ok', 'next_offset' => 0, 'total' => 0);

        $args = array(
            'type' => 'amateurs',
            'limit' => 100,
            'offset' => $offset
        );

        $data = $this->get($args);

        if($data->items) {
            $message['total'] = $data->total;

            global $wpdb;


            foreach($data->items as $item) {
                $check = $wpdb->get_row('SELECT id FROM ' . $this->database->table_amateurs . ' WHERE uid = ' . $item->u_id);

                if(!$check) {
                    $wpdb->insert(
                        $this->database->table_amateurs,
                        array(
                            'uid' => $item->u_id,
                            'username' => $item->nick
                        )
                    );
                }
            }

            $next_offset = $offset + 100;

            if($next_offset > ($data->total + 100)) {
                $next_offset = 0;
            }

            $message['next_offset'] = $next_offset;
        } else {
            $message['status'] = 'error';
        }

        return $message;
    }

    function getVideos($u_id, $offset = 0) {
        $args = array(
            'type' => 'videos',
            'limit' => 100,
            'offset' => $offset,
            'amateurId' => $u_id
        );

        $data = $this->get($args);

        //error_log('getVideos');
        //error_log(print_r($data, true));

        if(is_object($data) && isset($data->items)) {
            return $data->items;
        }
    }

    function saveVideos($data) {
        global $wpdb;

        if(!$data)
            return false;

        $status = array(
            'created' => '',
            'skipped' => '',
            'total' => ''
        );

        $c = 0;
        $s = 0;
        $i = 0;

        $wpdb->hide_errors();

        foreach($data as $item) {
            if($item->id && $item->url) {
                $check = $wpdb->get_row( 'SELECT id FROM ' . $this->database->table_videos . ' WHERE video_id = ' . $item->id);

                if($check) {
                    $s++;
                    $status['skipped'] = $s;
                    continue;
                }

                $wpdb->insert(
                    $this->database->table_videos,
                    array(
                        'object_id' => $item->u_id,
                        'video_id' => $item->id,
                        'object_name' => $item->nick,
                        'preview' => json_encode(array('normal' => $item->image, 'censored' => $item->image_sc)),
                        'title' => $item->title,
                        'duration' => $item->runtime,
                        'rating' => $item->rating,
                        'rating_count' => $item->rating_count,
                        'date' => $item->releasetime,
                        'description' => $item->description,
                        'link' => $item->url,
                        'language' => $item->language,
                        'tags' => json_encode($item->tags),
                        'categories' => json_encode($item->categories),
                        'imported' => '0'
                    )
                );

                if($wpdb->last_error) {
                    $s++;
                    $status['skipped'] = $s;
                } else {
                    $c++;
                    $status['created'] = $c;
                }

                $i++;
                $status['total'] = $i;
            }
        }

        return json_encode($status);
    }
}