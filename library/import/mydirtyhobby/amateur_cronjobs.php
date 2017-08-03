<?php
/**
 * Function: at_import_mdh_cronjob_initiate
 * Create cronjob for every active amateur
 */
add_action('at_import_cronjob_edit', 'at_import_mdh_amateur_cronjob_initiate', 10, 3);
function at_import_mdh_amateur_cronjob_initiate($id, $field, $value) {
    if($field == 'scrape') {
        global $wpdb;

        $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

        if($cron) {
            if($cron->network == 'mydirtyhobby' && $cron->type == 'user') {
                if($value == '1') {
                    if (! wp_next_scheduled ( 'at_import_mdh_scrape_videos_cronjob', array($id) )) {
                        wp_schedule_event(time(), 'daily', 'at_import_mdh_scrape_videos_cronjob', array($id));
                    }
                } else {
                    wp_clear_scheduled_hook('at_import_mdh_scrape_videos_cronjob', array($id));
                }
            }
        }
    }

    if($field == 'import') {
        global $wpdb;

        $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

        if($cron) {
            if($cron->network == 'mydirtyhobby' && $cron->type == 'user') {
                if($value == '1') {
                    if (! wp_next_scheduled ( 'at_import_mdh_import_videos_cronjob', array($id) )) {
                        wp_schedule_event(time(), '30min', 'at_import_mdh_import_videos_cronjob', array($id));
                    }
                } else {
                    wp_clear_scheduled_hook('at_import_mdh_import_videos_cronjob', array($id));
                }
            }
        }
    }
}

add_action('at_import_mdh_scrape_videos_cronjob', 'at_import_mdh_scrape_videos_cronjob');
function at_import_mdh_scrape_videos_cronjob($id) {
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
            'limit' => 1,
            'amateurId' => $u_id
        );

        $total = $import->getTotal($args);

        //error_log('NumPages');
        //error_log(print_r($total, true));

        if($total) {
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

            for($i=0; $i<=$num_pages; $i++) {
                $videos = $import->getAmateurVideos($u_id, $i * 100);
                $data = json_decode($import->saveVideos($videos, $u_id), TRUE);

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
                'object_id' => $u_id
            )
        );
    }

    error_log('Scraping: ' . $cron->name);
    error_log(print_r($results, true));

    echo json_encode($results);
}

add_action('at_import_mdh_import_videos_cronjob', 'at_import_mdh_import_videos_cronjob');
function at_import_mdh_import_videos_cronjob($id) {
    error_log('Cron ' . $id . ' started');

    global $wpdb;
    $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);
    $results = array('created' => 0, 'skipped' => 0, 'total' => 0, 'last_updated' => '');

    if($cron) {
        $database = new AT_Import_MDH_DB();
        $videos = $wpdb->get_results('SELECT * FROM ' . $database->table_videos . ' WHERE object_id = ' . $cron->object_id . ' AND imported != 1 LIMIT 0,50');

        if($videos) {
            foreach ($videos as $item) {
                $video = new AT_Import_Video($item->video_id);

                if ($video) {
                    // update cron table (processing)
                    $wpdb->update(
                        AT_CRON_TABLE,
                        array(
                            'processing' => 1,
                        ),
                        array(
                            'id' => $id
                        )
                    );

                    $post_id = $video->insert();

                    if ($post_id) {
                        // fields
                        $fields = at_import_mdh_prepare_video_fields($item->video_id);
                        if ($fields) {
                            $video->set_fields($fields);
                        }

                        // thumbnail
                        if ($item->preview) {
                            $preview = json_decode($item->preview);
                            $image = $preview->normal;

                            if ($image) {
                                $video->set_thumbnail($image);
                            }
                        }

                        // category
                        $categories = json_decode($item->categories);
                        if ($categories) {
                            foreach ($categories as $cat) {
                                if ($cat->name) {
                                    $video->set_term('video_category', $cat->name);
                                }
                            }
                        }

                        // actor
                        $video->set_term('video_actor', $item->object_name);

                        $results['created'] += 1;
                        $results['total'] += 1;
                        $results['last_activity'] = date("d.m.Y H:i:s");

                        // update video item as imported
                        $wpdb->update(
                            $database->table_videos,
                            array(
                                'imported' => 1
                            ),
                            array(
                                'video_id' => $item->video_id
                            )
                        );

                        // update cron table (processing)
                        $wpdb->update(
                            AT_CRON_TABLE,
                            array(
                                'processing' => 0,
                            ),
                            array(
                                'id' => $id
                            )
                        );
                    }
                } else {
                    // video already exist
                    $results['skipped'] += 1;
                    $results['total'] += 1;

                    // update video item as imported
                    $wpdb->update(
                        $database->table_videos,
                        array(
                            'imported' => 1
                        ),
                        array(
                            'video_id' => $item->video_id
                        )
                    );
                }
            }
        }

        $wpdb->update(
            AT_CRON_TABLE,
            array(
                'processing' => 0,
                'last_activity' => date("Y-m-d H:i:s")
            ),
            array(
                'object_id' => $cron->object_id
            )
        );
    }

    error_log('Importing: ' . $cron->name);
    error_log(print_r($results, true));
    error_log('Cron ' . $id . ' stopped');

    echo json_encode($results);
}