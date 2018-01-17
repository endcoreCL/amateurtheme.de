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
        set_time_limit(120); // try to set time limit to 120 seconds

        global $wpdb;
        $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

        if(!$cron) {
            wp_clear_scheduled_hook('at_import_big7_import_videos_cronjob', array($id));
            at_error_log('Cron ' . $id . ' deleted');
            exit;
        }

        $results = array('created' => 0, 'skipped' => 0, 'total' => 0, 'last_updated' => '');

        at_error_log('Started cronjob (BIG7, ' . $id . ')');

        if ($cron) {
            $import = new AT_Import_Big7_Crawler();
            $videos = $import->getAmateur($cron->object_id, true);

            if ($videos['videos']) {
                $actor = $videos['nickname'];

                foreach ($videos['videos'] as $item) {
                    $unique_id = md5($item['name']);

                    $video = new AT_Import_Video($unique_id);

                    if ($video->unique) {
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
                                        $video->set_term('video_category', $item['name']);
                                    }
                                }
                            }

                            // actor
                            $video->set_term('video_actor', $actor, 'big7', $cron->object_id);

                            $results['created'] += 1;
                            $results['total'] += 1;
                            $results['last_activity'] = date("d.m.Y H:i:s");

                            // update cron table (processing)
                            $wpdb->query('UPDATE ' . AT_CRON_TABLE . ' SET processing = 0, created = created+1, last_activity = "' . date("Y-m-d H:i:s") . '" WHERE id = "' . $id . '"');
                        }
                    } else {
                        // video already exist
                        $results['skipped'] += 1;
                        $results['total'] += 1;
                    }
                }
            }

            // check autor data
            $actor_need_update = $wpdb->get_var('SELECT term_id FROM cp_termmeta WHERE meta_key = "actor_id" AND meta_value = "' . $cron->object_id . '" AND (SELECT term_id FROM cp_termmeta WHERE meta_key="actor_last_updated" AND meta_value < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY)))');
            if($actor_need_update) {
                $post_id = 'video_actor_' . $actor_need_update;

                // image
                $actor_image = get_field('actor_image', $post_id);
                if(!$actor_image) {
                    if ($image = (isset($videos['foto']['large']) ? $videos['foto']['large'] : '')) {
                        $att_id = at_attach_external_image($image, null, false, $videos['nickname_sc'] . '-preview', array('post_title' => $videos['nickname_sc']));
                        if ($att_id) {
                            update_field('actor_image', $att_id, $post_id);
                        }
                    }
                }

                // gender
                if($gender = $videos['geschlecht']) {
                    if($gender == 'ts') {
                        $gender_decoded = __('Transexuell', 'amateurtheme');
                    } else if($gender == 'w') {
                        $gender_decoded = __('Weiblich', 'amateurtheme');
                    } else {
                        $gender_decoded = __('Männlich', 'amateurtheme');
                    }

                    update_field('actor_gender', $gender_decoded, $post_id);
                }

                // zipcode
                if($zipcode = $videos['plz']) {
                    update_field('actor_zipcode', $zipcode, $post_id);
                }

                // city
                if($ort = $videos['ort']) {
                    update_field('actor_city', $ort, $post_id);
                }

                // link
                if($link = $videos['link']) {
                    update_field('actor_profile_url', $link, $post_id);
                }

                update_field('actor_last_updated', time(), $post_id);
            }

            $wpdb->update(
                AT_CRON_TABLE,
                array(
                    'processing' => 0,
                    'last_activity' => date("Y-m-d H:i:s"),
                ),
                array(
                    'object_id' => $cron->object_id
                )
            );
        }

        at_error_log(print_r($results,true));

        at_error_log('Stoped cronjob (BIG7, ' . $id . ')');

        at_write_api_log('big7', $cron->name, 'Total: ' . $results['total'] . ', Imported: ' . $results['created'] . ' Skipped: ' . $results['skipped']);

        echo json_encode($results);
    }
}