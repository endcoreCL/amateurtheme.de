<?php
/**
 * Function: at_import_mdh_cronjob_initiate
 * Create cronjob for every active amateur
 */
add_action('at_import_cronjob_edit', 'at_import_mdh_cronjob_initiate', 10, 3);
function at_import_mdh_cronjob_initiate($id, $field, $value) {
    if($field == 'scrape') {
        global $wpdb;

        $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

        if($cron) {
            if($cron->network == 'mydirtyhobby') {
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
            if($cron->network == 'mydirtyhobby') {
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
        $admin = $wpdb->get_results("SELECT * from $wpdb->users LIMIT 0,1");
        $post_author = $admin[0]->ID;

        $u_id = $cron->object_id;
        $database = new AT_Import_MDH_DB();
        $videos = $wpdb->get_results('SELECT * FROM ' . $database->table_videos . ' WHERE object_id = '. $u_id . ' AND imported != 1 LIMIT 0,50');

        if($videos) {
            foreach($videos as $video) {
                $unique = at_import_mdh_check_if_video_exists($video->video_id);

                if($unique) {
                    $wpdb->update(
                        AT_CRON_TABLE,
                        array(
                            'processing' => 1,
                        ),
                        array(
                            'id' => $id
                        )
                    );

                    $args = array(
                        'post_title' => $video->title,
                        'post_status' => (get_option('at_mdh_post_status') ? get_option('at_mdh_post_status') : 'publish'),
                        'post_author' => $post_author,
                        'post_type' => 'video',
                    );

                    if(get_option('at_mdh_video_description') == '1') {
                        $args['post_content'] = $video->description;
                    }

                    $post_id = wp_insert_post($args);

                    if($post_id) {
                        add_post_meta($post_id, 'video_format', 'link');
                        add_post_meta($post_id, 'video_link', 'http://in.mydirtyhobby.com/track/' . get_option('at_mdh_naffcode') . '/?ac=user&ac2=previewvideo&uv_id=' . $video->video_id);
                        add_post_meta($post_id, 'video_dauer', $video->duration);
                        add_post_meta($post_id, 'video_bewertung', $video->rating);
                        add_post_meta($post_id, 'video_aufrufe', '0');
                        add_post_meta($post_id, 'video_src', 'mdh');
                        add_post_meta($post_id, 'video_unique_id', $video->video_id);


                        $image = '';
                        if($video->preview) {
                            $preview  = json_decode($video->preview);
                            $image = $preview->normal;
                        }

                        if($image) {
                            at_attach_external_image($image, $post_id, true, $post_id.'-vorschau', array('post_title' => $video->title));
                        }

                        //if($video_category != "-1") wp_set_object_terms($video_id, $video_category, 'video_category');
                        //if($video_actor != "-1") wp_set_object_terms($video_id, $video_actor, 'video_actor');

                        $wpdb->query(
                            "UPDATE $database->table_videos SET imported = '1' WHERE video_id = $video->video_id"
                        );

                        $results['created'] += 1;
                        $results['total'] += 1;
                        $results['last_activity'] = date("d.m.Y H:i:s");

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
                    $results['skipped'] += 1;
                    $results['total'] += 1;

                    $wpdb->query(
                        "UPDATE $database->table_videos SET imported = '1' WHERE video_id = $video->video_id"
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
                'object_id' => $u_id
            )
        );
    }

    error_log('Importing: ' . $cron->name);
    error_log(print_r($results, true));
    error_log('Cron ' . $id . ' stopped');

    echo json_encode($results);
}