<?php
/**
 * Social Share Signals
 * 
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if (!class_exists('SocialSignals')) {
	class SocialSignals {
		public function file_get_contents_curl($url)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, '10');
			$cont = curl_exec($ch);

			if (curl_error($ch)) {
				//die(curl_error($ch));
			}
			return $cont;
		}

		private function setTime($post_id)
		{
			$post_date = get_the_time('d.m.Y', $post_id);

			if (strtotime($post_date) < strtotime('-30 days')) {
				return 12 * HOUR_IN_SECONDS;
			} else {
				return 15 * MINUTE_IN_SECONDS;
			}

		}

		public function getSignals($post_id, $networks)
		{
			$output = array();

			if ($networks) {
				foreach ($networks as $network) {
					$signals = '';

					if (false === ($signals = get_transient('social_signal_' . $network . '_' . $post_id))) {
						if ($network == 'twitter') {

							$url = get_permalink($post_id);
							$json_string = $this->file_get_contents_curl('http://opensharecount.com/count.json?url=' . $url);
							$json = json_decode($json_string, true);
							$signals = isset($json['count']) ? intval($json['count']) : 0;
							set_transient('social_signal_' . $network . '_' . $post_id, $signals, $this->setTime($post_id));

						} else if ($network == 'fb_like' || $network == 'fb_share') {

                            $url = get_permalink($post_id);
                            $json_string = $this->file_get_contents_curl('https://graph.facebook.com/?id=' . $url);
                            $json = json_decode($json_string, true);
                            $signals = isset($json['share']['share_count'])?intval($json['share']['share_count']):0;
                            set_transient( 'social_signal_' . $network . '_' . $post_id, $signals, $this->setTime($post_id) );

						} else if ($network == 'gplus') {

							$url = get_permalink($post_id);
							$curl = curl_init();
							curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
							curl_setopt($curl, CURLOPT_POST, true);
							curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
							curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . rawurldecode($url) . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
							curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
							$curl_results = curl_exec($curl);
							curl_close($curl);
							$json = json_decode($curl_results, true);
							$signals = isset($json[0]['result']['metadata']['globalCounts']['count']) ? intval($json[0]['result']['metadata']['globalCounts']['count']) : 0;

						} else if ($network == 'pinterest') {

							$url = get_permalink($post_id);
							$json_string = $this->file_get_contents_curl('http://api.pinterest.com/v1/urls/count.json?url=' . $url);
							$json = json_decode($json_string, true);
							$signals = isset($json[0]['count']) ? intval($json[0]['count']) : 0;

						} else if ($network == 'linkedin') {

							$url = get_permalink($post_id);
							$json_string = $this->file_get_contents_curl("http://www.linkedin.com/countserv/count/share?url=" . $url . "&format=json");
							$json = json_decode($json_string, true);
							$signals = isset($json['count']) ? intval($json['count']) : 0;

						} else if ($network == 'xing') {

							$url = get_permalink($post_id);
							$string = $this->file_get_contents_curl('https://www.xing-share.com/app/share?op=get_share_button;counter=top;lang=de;url=' . $url);
							preg_match_all("/<span class=\"xing-count top\".*span>/", $string, $matches);
							$signals = isset($matches[0][0]) ? strip_tags($matches[0][0]) : 0;

						} else {

							$signals = '';

						}

						if ($network != 'wa')
							set_transient('social_signal_' . $network . '_' . $post_id, $signals, $this->setTime($post_id));
					}

					$output[$network] = $signals;
				}
			}

			return $output;
		}
	}
}