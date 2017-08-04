<?php
/**
 * Loading big7 amateur cronjob functions
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if ( ! function_exists( 'at_import_big7_amateur_cronjob_initiate' ) ) {
    /**
     * at_import_big7_amateur_cronjob_initiate function
     *
     */
    add_action('at_import_cronjob_edit', 'at_import_big7_amateur_cronjob_initiate', 10, 3);
    function at_import_big7_amateur_cronjob_initiate($id, $field, $value) {
        if ($field == 'import') {
            global $wpdb;

            $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

            if ($cron) {
                if ($cron->network == 'big7' && $cron->type == 'user') {
                    if ($value == '1') {
                        if (!wp_next_scheduled('at_import_big7_import_videos_cronjob', array($id))) {
                            wp_schedule_event(time(), '30min', 'at_import_big7_import_videos_cronjob', array($id));
                        }
                    } else {
                        wp_clear_scheduled_hook('at_import_big7_import_videos_cronjob', array($id));
                    }
                }
            }
        }
    }
}

if ( ! function_exists( 'at_import_big7_import_videos_cronjob' ) ) {
    /**
     * at_import_big7_import_videos_cronjob function
     *
     */
    add_action('at_import_big7_import_videos_cronjob', 'at_import_big7_import_videos_cronjob');
    function at_import_big7_import_videos_cronjob($id) {
        global $wpdb;
        $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);
        $results = array('created' => 0, 'skipped' => 0, 'total' => 0, 'last_updated' => '');

        error_log('Started cronjob (' . $id . ')');

        if ($cron) {
            $import = new AT_Import_Big7_Crawler();
            $videos = $import->getAmateur($cron->object_id, true);

            if ($videos['videos']) {
                $actor = $videos['nickname'];

                foreach ($videos['videos'] as $item) {
                    $unique_id = md5($item['name']);

                    $video = new AT_Import_Video($unique_id);

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

                        $title = (get_option('at_big7_fsk18') == 1 ? $item['name'] : $item['name_sc']);
                        $description = (get_option('at_big7_fsk18') == 1 ? $item['beschreibung'] : $item['beschreibung_sc']);

                        if(get_option('at_big7_video_description') != '1') {
                            $description = '';
                        }

                        $post_id = $video->insert($title, $description);

                        if ($post_id) {
                            // fields
                            $fields = at_import_big7_prepare_video_fields($item);
                            if ($fields) {
                                $video->set_fields($fields);
                            }

                            // thumbnail
                            if ($item['vorschaubild_hc']) {
                                $video->set_thumbnail($item['vorschaubild_hc']);
                            }

                            // category
                            $categories = $item['kategorien'];
                            if ($categories) {
                                foreach ($categories as $cat) {
                                    if ($cat['name']) {
                                        $video->set_term('video_category', $cat['name']);
                                    }
                                }
                            }

                            // actor
                            $video->set_term('video_actor', $actor);

                            $results['created'] += 1;
                            $results['total'] += 1;
                            $results['last_activity'] = date("d.m.Y H:i:s");

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

        error_log(print_r($results,true));

        error_log('Stoped cronjob (' . $id . ')');

        at_write_api_log('big7', $cron->name, 'Total: ' . $results['total'] . ', Imported: ' . $results['created'] . ' Skipped: ' . $results['skipped']);

        echo json_encode($results);
    }
}