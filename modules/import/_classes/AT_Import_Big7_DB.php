<?php
/**
 * Created by PhpStorm.
 * User: christianlang
 * Date: 25.07.17
 * Time: 14:12
 */
class AT_Import_Big7_DB {
	public function __construct() {
		// tables
		global $wpdb;
		$this->table_amateurs = $wpdb->prefix . 'import_big7_amateurs';
		$this->table_videos = $wpdb->prefix . 'import_big7_videos';

		// ajax
		add_action('wp_ajax_at_import_big7_db_amateur', array( &$this, 'generateAmateurDB' ));
		add_action('wp_ajax_at_import_big7_db_video', array( &$this, 'generateVideoDB' ));

		// initiate cronjob func
		add_action('at_import_big7_generate_amateur_db', array( &$this, 'generateAmateurDB' ));
		add_action('at_import_big7_generate_video_db', array( &$this, 'generateVideoDB' ));

		// initiate cronjob
		if (!wp_next_scheduled('at_import_big7_generate_amateur_db')) {
			wp_schedule_event(time(), 'weekly', 'at_import_big7_generate_amateur_db');
		}
		if (!wp_next_scheduled('at_import_big7_generate_video_db')) {
			wp_schedule_event(time(), 'weekly', 'at_import_big7_generate_video_db');
		}
	}

	public function generateAmateurDB() {
		global $wpdb;

		$crawler = new AT_Import_Big7_Crawler();
		$amateurs = $crawler->jsonAmateurs();

		if($amateurs) {
			at_error_log('Started cronjob (BIG7, generateAmateurDB)');

			foreach($amateurs as $amateur) {
				$uid = intval($amateur['u_id']);
				$username = (isset($amateur['nickname']) ? $amateur['nickname'] : '');
				$username_sc = (isset($amateur['nickname_sc']) ? $amateur['nickname_sc'] : '');
				$gender = (isset($amateur['geschlecht']) ? $amateur['geschlecht'] : '');
				$birthday = (isset($amateur['geburtstag']) ? $amateur['geburtstag'] : '');
				$zipcode = (isset($amateur['plz']) ? $amateur['plz'] : '');
				$city = (isset($amateur['ort']) ? $amateur['ort'] : '');
				$country = (isset($amateur['land']) ? $amateur['land'] : '');
				$state = (isset($amateur['bundesland']) ? $amateur['bundesland'] : '');
				$firstname = (isset($amateur['vorname']) ? $amateur['vorname'] : '');
				$size = (isset($amateur['groesse']) ? $amateur['groesse'] : '');
				$weight = (isset($amateur['gewicht']) ? $amateur['gewicht'] : '');
				$eyecolor = (isset($amateur['augenfarbe']) ? $amateur['augenfarbe'] : '');
				$haircolor = (isset($amateur['haarfarbe']) ? $amateur['haarfarbe'] : '');
				$sex = (isset($amateur['sex']) ? $amateur['sex'] : '');
				$shaved = ($amateur['intimrasur'] == 'ja' ? 1 : 0);
				$body = (isset($amateur['figur']) ? $amateur['figur'] : '');
				$piercings = (isset($amateur['piercings']) ? $amateur['piercings'] : '');
				$tattoos = (isset($amateur['tattoos']) ? $amateur['tattoos'] : '');
				$preferences = (isset($amateur['vorlieben']) ? $amateur['vorlieben'] : '');
				$sexfantasies = (isset($amateur['sexfantasie']) ? $amateur['sexfantasie'] : '');
				$penis = (isset($amateur['penis']) ? intval($amateur['penis']) : 0);
				$aboutme = (isset($amateur['uebermich']) ? $amateur['uebermich'] : '');
				$aboutme_sc = (isset($amateur['uebermich_sc']) ? $amateur['uebermich_sc'] : '');
				$languages = (isset($amateur['sprachen']) ? $amateur['sprachen'] : '');
				$link = (isset($amateur['link']) ? $amateur['link'] : '');
				$is_bdsm = (isset($amateur['ist_bdsm']) ? intval($amateur['ist_bdsm']) : 0);
				$image_small = (isset($amateur['fotos'][0]['small']) ? $amateur['fotos'][0]['small'] : '');
				$image_medium = (isset($amateur['fotos'][0]['medium']) ? $amateur['fotos'][0]['medium'] : '');
				$image_large = (isset($amateur['fotos'][0]['large']) ? $amateur['fotos'][0]['large'] : '');

				$wpdb->query(
					"
					REPLACE into {$this->table_amateurs}
					(uid, username, username_sc, gender, birthday, zipcode, city, country, state, firstname, size, weight, eyecolor, haircolor, sex, shaved, body, piercings, tattoos, preferences, sexfantasies , penis, aboutme, aboutme_sc, languages, link, is_bdsm, image_small, image_medium, image_large)
					VALUES 
					({$uid} ,'" . esc_sql($username) . "', '" . esc_sql($username_sc) . "', '{$gender}', '{$birthday}', '{$zipcode}', '{$city}', '{$country}', '{$state}', '{$firstname}', '{$size}', '{$weight}', '{$eyecolor}', '{$haircolor}', '{$sex}', {$shaved}, '{$body}', '{$piercings}', '{$tattoos}', '" . esc_sql($preferences) . "', '" . esc_sql($sexfantasies) . "', {$penis}, '" . esc_sql($aboutme) . "', '" . esc_sql($aboutme_sc) . "', '{$languages}', '{$link}', {$is_bdsm}, '{$image_small}', '{$image_medium}', '{$image_large}')
					"
				);
			}

			at_error_log('Stopped cronjob (BIG7, generateAmateurDB), imported ' . count($amateurs) . ' amateurs.');
		}
	}

	public function generateVideoDB() {
		global $wpdb;

		$crawler = new AT_Import_Big7_Crawler();
		$data = $crawler->jsonVideos();

		if($data) {
			foreach($data as $item) {
				$videos = $item['videos'];

				if($videos) {
					at_error_log('Started cronjob (BIG7, generateVideoDB)');

					$uid = $item['u_id'];

					foreach($videos as $video) {
						$categories = array();
						if($video['kategorien']) {
							foreach($video['kategorien'] as $cat) {
								$categories[] = $cat['name'];
							}
						}

						$video_id = md5($uid . $video['name']);
						$preview = $video['vorschaubild_hc'];
						$preview_webm = $video['vorschauvideo']['webm'];
						$preview_mp4 = $video['vorschauvideo']['mp4'];
						$title = $video['name'];
						$title_sc = $video['name_sc'];
						$duration = $video['dauer'];
						$rating = $video['bewertung'];
						$rating_count = $video['anz_bewertung'];
						$date = $video['veroeffentlicht'];
						$description = $video['beschreibung'];
						$description_sc = $video['beschreibung_sc'];
						$link = $video['direktlink'];
						$categories = implode(',', $categories);

						$wpdb->query(
							"
							REPLACE into {$this->table_videos}
							(uid, video_id, preview, preview_webm, preview_mp4, title, title_sc, duration, rating, rating_count, date, description, description_sc, link, categories)
							VALUES 
							  ({$uid} ,'{$video_id}', '{$preview}', '{$preview_webm}', '{$preview_mp4}', '" . esc_sql($title) . "', '" . esc_sql($title_sc) . "', '{$duration}', '{$rating}', '{$rating_count}', '{$date}', '" . esc_sql($description) . "', '" . esc_sql($description_sc) . "', '{$link}', '{$categories}')
							"
						);
					}

					at_error_log('Stopped cronjob (BIG7, generateVideoDB), imported ' . count($videos) . ' videos.');
				}
			}
		}
	}
}

$AT_Import_Big7_DB = new AT_Import_Big7_DB();