<?php
/**
 * Created by PhpStorm.
 * User: christianlang
 * Date: 04.08.17
 * Time: 15:22
 */
class AT_Import_AC_Crawler {
    public function __construct() {
        // folder
        $upload_dir = wp_upload_dir();
        $this->folder = $upload_dir['basedir'] . '/big7';

        $this->wmb = get_option('at_big7_wmb');
        $this->amateurs = 'https://api.campartner.com/content/v3/amateurs';
        $this->amateur = 'https://api.campartner.com/content/v3/amateur/';

        $this->json_result = array();
    }

    function json_callback($item) {
        $this->json_result[] = $item;
    }

    function get($type = 'amateurs', $filter = array()) {
        if($type == 'amateur') {
            $url = $this->amateur;
        } else {
        	$url = $this->amateurs;
        }

        if(!empty($filter)) {
	        $url .= '?' . http_build_query( $filter );
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

        return json_decode($response, true);
    }

	function jsonAmateurs($filter = array()) {
    	// catch age
		$age = array();
		if(isset($filter['age_from'])) {
			$age[] = $filter['age_from'];
			unset($filter['age_from']);
		}
		if(isset($filter['age_to'])) {
			$age[] = $filter['age_to'];
			unset($filter['age_to']);
		}
		if(!empty($age)) {
			$filter['age'] = implode('-', $age);
		}

		$data = $this->get('amateurs', $filter);

		if($data) {
			return $data;
		}

		return false;
	}

    function jsonVideos() {
	    $data = $this->read('videos.json');

	    if($data) {
	    	return $data;
	    }

	    return false;
    }
}