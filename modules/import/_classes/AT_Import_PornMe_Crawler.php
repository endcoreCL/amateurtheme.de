<?php
/**
 * Created by PhpStorm.
 * User: christianlang
 * Date: 20.03.18
 * Time: 10:53
 */

class AT_Import_PornMe_Crawler {
	public function __construct() {
		$this->vx_id = get_option('at_pornme_wmb');
		$this->sec = md5("xfo7%bdYmO!p3DkwT4=l2s" . $this->vx_id . "9mG3lH3f2&Ma!md6G=76(2");
		$this->urls = array(
			'user' => 'https://api.pornme.com/webmaster.api.php?type=amateurs&vx_id=' . $this->vx_id . '&sec=' . $this->sec,
			'video' => 'http://api.pornme.com/webmaster.api.php?type=videos&vx_id=' . $this->vx_id . '&&sec=' . $this->sec,
			'tag' => 'http://api.pornme.com/webmaster.api.php?type=categories&vx_id=' . $this->vx_id . '&&sec=' . $this->sec,
		);
	}

	public function get($url, $page = 0, $param = array()) {
		if($param) {
			foreach($param as $k => $v) {
				$url .= '&' . $k . '=' . $v;
			}
		}

		$url .= '&page=' . $page;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$html = curl_exec($curl);

		if(curl_errno($curl)) {
			return 'Curl error: ' . curl_error($curl);
		}

		curl_close($curl);

		if($html) {
			$data = json_decode($html, true);
			if(is_array($data)) {
				if($data['code'] == '200' && $data['status'] == 'success') {
					return $data['data'];
				}
			}
		}

		return false;
	}

	public function getNumPages($type = 'user', $param = array()) {
		if($type == 'video') {
			$name = $param['UID'];
		} else {
			$name = $type;
		}

		if ( false === ( $value = get_transient( 'at_pornme_' . $name . '_num_pages' ) ) ) {
			$data = $this->get($this->urls[$type], 0, $param);

			if(!$data) {
				return false;
			}

			$value = $data['total_sites'];

			set_transient( 'at_pornme_' . $name . '_num_pages', $value, 6 * HOUR_IN_SECONDS );
		}

		return $value;
	}

	function getAmateurVideos($u_id) {
		global $wpdb;

		$database = new AT_Import_PornMe_DB();

		$data = $wpdb->get_results(
			"
    		SELECT * FROM {$database->table_videos}
    	  	WHERE source_id = {$u_id}
    		",
			OBJECT
		);

		if($data) {
			return $data;
		}

		return false;
	}

	public function jsonAmateurs($page = 0) {
		$data = $this->get($this->urls['user'], $page);

		if($data) {
			return $data;
		}

		return false;
	}

	public function jsonVideos($uid, $page = 0) {
		$data = $this->get($this->urls['video'], $page, array('UID' => $uid));

		if($data) {
			return $data;
		}

		return false;
	}

	public function saveVideos($user_id, $data, $source_type = 'user') {
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

		$database = new AT_Import_PornMe_DB();

		foreach($data as $item) {
			if($item['VID'] && $item['link']) {

				$wpdb->insert(
					$database->table_videos,
					array(
						'source_id' => $user_id,
						'source_type' => $source_type,
						'video_id' => $item['VID'],
						'preview' => $item['previewpic'],
						'title' => $item['title'],
						'duration' => $item['duration'],
						'rating' => $item['rate'],
						'date' => $item['approvetime'],
						'description' => $item['description'],
						'link' => $item['link'],
						'tags' => json_encode($item['tags']),
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