<?php
/**
 * Function at_import_mdh_scripts
 * Load import scripts
 */
add_action('admin_enqueue_scripts', 'at_import_mdh_scripts');
function at_import_mdh_scripts($page) {
    if(strpos($page, 'at_import_mydirtyhobby') === false) return;

    wp_enqueue_script( 'at-mydirtyhobby', get_template_directory_uri() . '/library/import/assets/js/mydirtyhobby.js');
}

add_action('wp_ajax_at_import_mdh_crawl_amateurs', 'at_import_mdh_crawl_amateurs');
function at_import_mdh_crawl_amateurs() {
    $offset = (isset($_GET['offset']) ? $_GET['offset'] : '0');
    $import = new AT_Import_MDH_Crawler();
    $response = $import->crawlAmateurs($offset);
    echo json_encode($response);
    exit;
}

add_action('wp_ajax_at_import_mdh_get_videos', 'at_import_mdh_get_videos');
function at_import_mdh_get_videos() {
    $u_id = (isset($_GET['u_id']) ? $_GET['u_id'] : false);

    if(!$u_id) {
        echo json_encode(array('status', 'error'));
        exit;
    }

    $import = new AT_Import_MDH_Crawler();
    $response = $import->getVideos($u_id);
    echo json_encode($response);
    exit;
}

/**
 * Function: at_import_mdh_cronjob_initiate
 * Create cronjob for every active amateur
 */
add_action('at_import_cronjob_edit', 'at_import_mdh_cronjob_initiate', 10, 3);
function at_import_mdh_cronjob_initiate($id, $field, $value) {
    if($field == 'import') {
        global $wpdb;

        $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

        if($cron) {
            if($cron->network == 'mydirtyhobby') {
                if($value == '1') {
                    if (! wp_next_scheduled ( 'my_hourly_event' )) {
                        wp_schedule_event(time(), '30min', 'at_import_mdh_save_videos_cronjob', array($id));
                    }
                } else {
                    wp_clear_scheduled_hook('at_import_mdh_save_videos_cronjob', array($id));
                }
            }
        }
    }
}

add_action('at_import_mdh_save_videos_cronjob', 'at_import_mdh_save_videos_cronjob');
function at_import_mdh_save_videos_cronjob($id) {
    global $wpdb;
    $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);
    $results = array('created' => 0, 'skipped' => 0, 'total' => 0, 'last_updated' => '');

    if($cron) {
        $u_id = $cron->object_id;

        // initiate import
        $import = new AT_Import_MDH_Crawler();

        // get total
        $args = array(
            'type' => 'videos',
            'limit' => 1
        );
        $num_pages = $import->getTotal($args);

        error_log('NumPages');
        error_log(print_r($num_pages, true));

        if($num_pages) {
            $wpdb->update(
                AT_CRON_TABLE,
                array(
                    'processing' => 1,
                ),
                array(
                    'id' => $id
                )
            );

            $num_pages = ceil($num_pages / 100);

            for($i=0; $i<=$num_pages; $i++) {
                $videos = $import->getVideos($u_id, $i * 100);
                $data = json_decode($import->saveVideos($videos), TRUE);

                if($data['created']) {
                    $results['created'] = $results['created'] + $data['created'];
                }

                if($data['skipped']) {
                    $results['skipped'] = $results['skipped'] + $data['skipped'];
                }

                if($data['total']) {
                    $results['total'] = $results['total'] + $data['total'];
                }

                $results['last_activity'] = date("d.m.Y H:i:s");

                break;
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
                    'object_id' => $u_id
                )
            );
        }
    }

    echo json_encode($results);

    error_log('save_videos_results');
    error_log(print_r($results, true));

    die();
}