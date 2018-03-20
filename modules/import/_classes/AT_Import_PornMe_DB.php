<?php
/**
 * Created by PhpStorm.
 * User: christianlang
 * Date: 25.07.17
 * Time: 14:12
 */
class AT_Import_PornMe_DB {
	public function __construct() {
		// tables
		global $wpdb;
		$this->table_amateurs = $wpdb->prefix . 'import_pornme_amateurs';
		$this->table_videos = $wpdb->prefix . 'import_pornme_videos';
		$this->table_tags = $wpdb->prefix . 'import_pornme_tags';

		// ajax
		add_action('wp_ajax_at_import_pornme_db_amateur', array( &$this, 'generateAmateurDB' ));

		// initiate cronjob func
		add_action('at_import_pornme_generate_amateur_db', array( &$this, 'generateAmateurDB' ));

		// initiate cronjob
		if (!wp_next_scheduled('at_import_pornme_generate_amateur_db')) {
			wp_schedule_event(time(), 'weekly', 'at_import_pornme_generate_amateur_db');
		}
	}

	public function generateAmateurDB() {
		global $wpdb;

		$crawler = new AT_Import_PornMe_Crawler();
		$total_pages = $crawler->getNumPages('user');

		if($total_pages) {
			$wpdb->hide_errors();

			for($i=0; $i<=$total_pages; $i++) {
				$amateurs = $crawler->jsonAmateurs($i);

				if(isset($amateurs['amateurs'])) {
					foreach($amateurs['amateurs'] as $amateur) {
						$uid = intval($amateur['UID']);
						$username = $amateur['username'];

						$wpdb->query(
							"
							INSERT INTO {$this->table_amateurs} (uid, username) VALUES ({$uid} ,'" . esc_sql($username) . "')
							ON DUPLICATE KEY UPDATE username = '" . esc_sql($username) . "'
							"
						);
					}
				}
			}
		}
	}
}

$AT_Import_PornMe_DB = new AT_Import_PornMe_DB();