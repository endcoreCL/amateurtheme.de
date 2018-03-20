<?php
/**
 * Loading mydirtyhobby category cronjob functions
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if ( ! function_exists( 'at_import_mdh_category_cronjob_initiate' ) ) {
    /**
     * at_import_mdh_category_cronjob_initiate function
     *
     */
    add_action('at_import_cronjob_edit', 'at_import_mdh_category_cronjob_initiate', 10, 3);
    function at_import_mdh_category_cronjob_initiate($id, $field, $value) {
        if ($field == 'scrape') {
            global $wpdb;

            $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

            if ($cron) {
                if ($cron->network == 'mydirtyhobby' && $cron->type == 'category') {
                    if ($value == '1') {
                        if (!wp_next_scheduled('at_import_mdh_scrape_category_videos_cronjob', array($id))) {
                            wp_schedule_event(time(), 'daily', 'at_import_mdh_scrape_category_videos_cronjob', array($id));
                        }
                    } else {
                        wp_clear_scheduled_hook('at_import_mdh_scrape_category_videos_cronjob', array($id));
                    }
                }
            }
        }

        if ($field == 'import') {
            global $wpdb;

            $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

            if ($cron) {
                if ($cron->network == 'mydirtyhobby' && $cron->type == 'category') {
                    if ($value == '1') {
                        if (!wp_next_scheduled('at_import_mdh_import_videos_cronjob', array($id))) {
                            wp_schedule_event(time(), '30min', 'at_import_mdh_import_videos_cronjob', array($id));
                        }
                    } else {
                        wp_clear_scheduled_hook('at_import_mdh_import_videos_cronjob', array($id));
                    }
                }
            }
        }
    }
}

if ( ! function_exists( 'at_import_mdh_category_cronjob_delete' ) ) {
	/**
	 * at_import_mdh_category_cronjob_delete function
	 *
	 */
	add_action('at_import_cronjob_delete', 'at_import_mdh_category_cronjob_delete', 10, 1);
	function at_import_mdh_category_cronjob_delete($id) {
		global $wpdb;

		$cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

		if ($cron) {
			if ($cron->network == 'mydirtyhobby' && $cron->type == 'category') {
				wp_clear_scheduled_hook('at_import_mdh_scrape_category_videos_cronjob', array($id));
			}
		}
	}
}

if ( ! function_exists( 'at_import_mdh_scrape_category_videos_cronjob' ) ) {
    /**
     * at_import_mdh_scrape_category_videos_cronjob function
     *
     */
    add_action('at_import_mdh_scrape_category_videos_cronjob', 'at_import_mdh_scrape_category_videos_cronjob');
    function at_import_mdh_scrape_category_videos_cronjob($id) {
        global $wpdb;
        $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);
        $results = array('created' => 0, 'skipped' => 0, 'total' => 0, 'last_updated' => '');

        if ($cron) {
            $c_id = $cron->object_id;

            // initiate import
            $import = new AT_Import_MDH_Crawler();

            // get total
            $args = array(
                'type' => 'category',
                'limit' => 1,
                'categoryId' => $c_id
            );

            $total = $import->getTotal($args);

            if ($total) {
                $wpdb->update(
                    AT_CRON_TABLE,
                    array(
                        'processing' => 1,
                    ),
                    array(
                        'id' => $id
                    )
                );

                $num_pages = ceil($total / 100);

                $last_pos = 0;
                if ($cron->last_pos > 0) $last_pos = $cron->last_pos;

                for ($i = $last_pos; $i <= $num_pages; $i++) {
                    $videos = $import->getCategoryVideos($c_id, $i * 100);
                    $data = json_decode($import->saveVideos($videos, $c_id), TRUE);

                    if ($data['created']) {
                        $results['created'] = $results['created'] + $data['created'];
                    }

                    if ($data['skipped']) {
                        $results['skipped'] = $results['skipped'] + $data['skipped'];
                    }

                    if ($data['total']) {
                        $results['total'] = $results['total'] + $data['total'];
                    }

                    $results['last_activity'] = date("d.m.Y H:i:s");

                    $wpdb->query(
                        "UPDATE " . AT_CRON_TABLE . " SET last_pos = '$i' WHERE id = $id"
                    );
                }

                if ($i > $num_pages) {
                    // reset last_pos if maximum reached
                    $wpdb->query(
                        "UPDATE " . AT_CRON_TABLE . " SET last_pos = '0' WHERE id = $id"
                    );
                }
            }

            $wpdb->update(
                AT_CRON_TABLE,
                array(
                    'created' => $results['created'],
                    'skipped' => $results['skipped'],
                    'processing' => 0,
                    'last_activity' => date("Y-m-d H:i:s")
                ),
                array(
                    'object_id' => $c_id
                )
            );
        }

        echo json_encode($results);
    }
}