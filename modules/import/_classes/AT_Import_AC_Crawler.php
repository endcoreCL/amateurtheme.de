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
        $this->amateur = 'https://api.campartner.com/content/v3/amateur';

        $this->json_result = array();
    }

    function json_callback($item) {
        $this->json_result[] = $item;
    }

    function get($type = 'amateurs', $filter = array()) {
        if($type == 'amateur') {
            $url = $this->amateur . '/' . $filter;
        } else {
        	$url = $this->amateurs;

	        if(!empty($filter)) {
		        $url .= '?' . http_build_query( $filter );
	        }
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

    function jsonMedias($user) {
	    $data = $this->get('amateur', $user);

	    if($data) {
	    	return $data;
	    }

	    return false;
    }

	public function saveMedias($user_id, $username, $data, $type = 'movies') {
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

		$database = new AT_Import_AC_DB();

		foreach($data as $item) {
			if($item['id']) {
				if($item['type'] != $type) {
					// skip wrong type
					continue;
				}

				$check = $wpdb->get_var('SELECT id FROM ' . $database->table_media . ' WHERE media_id = ' . $item['id']);

				if($check) {
					$s++;

					$status['skipped'] = $s;

					continue; // video already exists
				}

				$wpdb->insert(
					$database->table_media,
					array(
						'uid' => $user_id,
						'nickname' => $username,
						'media_id' => $item['id'],
						'title' => $item['name']['18']['de'],
						'title_sc' => $item['name']['16']['de'],
						'description' => $item['description']['18']['de'],
						'description_sc' => $item['description']['16']['de'],
						'date' => $item['publishingDate'],
						'rating' => $item['rating'],
						'type' => $item['type'],
						'votes' => $item['votes'],
						'categories' => json_encode($item['categories']),
						'preview' => $item['thumbs']['18']['large'],
						'preview_sc' => $item['thumbs']['16']['large'],
						'url' => $item['url'],
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