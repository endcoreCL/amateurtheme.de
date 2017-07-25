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
        $this->url = 'https://www.mydirtyhobby.com/api/amateurs/?naff=' . $this->naffcode;

        // tables
        global $wpdb;
        $this->table_amateurs = $wpdb->prefix . 'import_mdh_amateurs';
    }

    function get($params = array()) {
        $url = $this->url;

        if(!empty($params)) {
            $url = $this->url . '&'  . http_build_query($params);
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

    function amateurs_total() {
        $args = array(
            'limit' => 1
        );

        $data = $this->get($args);

        if($data) {
            return $data->total;
        }

        return false;
    }


    function amateurs_crawl($offset = 0) {
        $args = array(
            'limit' => 100,
            'offset' => $offset
        );

        $data = $this->get($args);

        if($data->items) {
            global $wpdb;

            foreach($data->items as $item) {
                $wpdb->insert(
                    $this->table_amateurs,
                    array(
                        'uid' => $item->u_id,
                        'username' => $item->nick
                    )
                );
            }
        }
    }
}