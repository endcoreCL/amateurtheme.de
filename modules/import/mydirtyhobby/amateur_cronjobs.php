<?php
/**
 * Loading mydirtyhobby amateur cronjob functions
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if ( ! function_exists( 'at_import_mdh_amateur_cronjob_initiate' ) ) {
    /**
     * at_import_mdh_amateur_cronjob_initiate function
     *
     */
    add_action('at_import_cronjob_edit', 'at_import_mdh_amateur_cronjob_initiate', 10, 3);
    function at_import_mdh_amateur_cronjob_initiate($id, $field, $value) {
        if ($field == 'scrape') {
            global $wpdb;

            $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

            if ($cron) {
                if ($cron->network == 'mydirtyhobby' && $cron->type == 'user') {
                    if ($value == '1') {
                        if (!wp_next_scheduled('at_import_mdh_scrape_videos_cronjob', array($id))) {
                            wp_schedule_event(time(), 'daily', 'at_import_mdh_scrape_videos_cronjob', array($id));
                        }
                    } else {
                        wp_clear_scheduled_hook('at_import_mdh_scrape_videos_cronjob', array($id));
                    }
                }
            }
        }

        if ($field == 'import') {
            global $wpdb;

            $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

            if ($cron) {
                if ($cron->network == 'mydirtyhobby' && $cron->type == 'user') {
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

if ( ! function_exists( 'at_import_mdh_scrape_videos_cronjob' ) ) {
    /**
     * at_import_mdh_scrape_videos_cronjob function
     *
     */
    add_action('at_import_mdh_scrape_videos_cronjob', 'at_import_mdh_scrape_videos_cronjob');
    function at_import_mdh_scrape_videos_cronjob($id) {
        global $wpdb;
        $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

        at_error_log(print_r($cron, true));

        if(!$cron) {
            wp_clear_scheduled_hook('at_import_mdh_scrape_videos_cronjob', array($id));
            at_error_log('Cron ' . $id . ' deleted');
            exit;
        }

        $results = array('created' => 0, 'skipped' => 0, 'total' => 0, 'last_updated' => '');

        if ($cron) {
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

                for ($i = 0; $i <= $num_pages; $i++) {
                    $videos = $import->getAmateurVideos($u_id, $i * 100);
                    $data = json_decode($import->saveVideos($videos, $u_id), TRUE);

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

        echo json_encode($results);
    }
}

if ( ! function_exists( 'at_import_mdh_import_videos_cronjob' ) ) {
    /**
     * at_import_mdh_import_videos_cronjob function
     *
     */
    add_action('at_import_mdh_import_videos_cronjob', 'at_import_mdh_import_videos_cronjob');
    function at_import_mdh_import_videos_cronjob($id) {
        set_time_limit(120); // try to set time limit to 120 seconds

        global $wpdb;
        $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

        if(!$cron) {
            wp_clear_scheduled_hook('at_import_mdh_import_videos_cronjob', array($id));
            at_error_log('Cron ' . $id . ' deleted');
            exit;
        }

        $results = array('created' => 0, 'skipped' => 0, 'total' => 0, 'last_updated' => '');

        at_error_log('Started cronjob (MDH, ' . $id . ')');

        if ($cron) {
            $database = new AT_Import_MDH_DB();
            $videos = $wpdb->get_results('SELECT * FROM ' . $database->table_videos . ' WHERE source_id = "' . $cron->object_id . '" AND imported != 1 LIMIT 0,50');

            if ($videos) {
                foreach ($videos as $item) {
                    $video = new AT_Import_Video($item->video_id);

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

                        $post_id = $video->insert($item->title, $item->description);

                        if ($post_id) {
                            // fields
                            $fields = at_import_mdh_prepare_video_fields($item->video_id);
                            if ($fields) {
                                $video->set_fields($fields);
                            }

                            // thumbnail
                            if ($item->preview) {
                                $preview = json_decode($item->preview);
                                $image = $preview->censored;

                                if (get_option('at_mdh_fsk18') == '1') {
                                    $image = $preview->normal;
                                }

                                if (is_string($image)) {
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
                            $video->set_term('video_actor', $item->object_name, 'mdh', $cron->object_id);

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

            // check autor data
            $actor_need_update = $wpdb->get_var('SELECT term_id FROM cp_termmeta WHERE meta_key = "actor_id" AND meta_value = "' . $cron->object_id . '" AND (SELECT term_id FROM cp_termmeta WHERE meta_key="actor_last_updated" AND meta_value < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY)))');
            if($actor_need_update) {
                $crawl = new AT_Import_MDH_Crawler();
                $amateur = $crawl->getAmateurDetails($cron->object_id);

                if ($amateur) {
                    $amateur = $amateur[0];

                    at_error_log(print_r($amateur, true));

                    $post_id = 'video_actor_' . $actor_need_update;

                    // image
                    $actor_image = get_field('actor_image', $post_id);
                    if(!$actor_image) {
                        if ($image = (isset($amateur->images->bild1) ? $amateur->images->bild1 : '')) {
                            $att_id = at_attach_external_image($image, null, false, $amateur->nick . '-preview', array('post_title' => $amateur->nick));
                            if ($att_id) {
                                update_field('actor_image', $att_id, $post_id);
                            }
                        }
                    }

                    // gender
                    if ($gender = $amateur->gender) {
                        if ($gender == 'T') {
                            $gender_decoded = __('Transexuell', 'amateurtheme');
                        } else if ($gender == 'F') {
                            $gender_decoded = __('Weiblich', 'amateurtheme');
                        } else {
                            $gender_decoded = __('Männlich', 'amateurtheme');
                        }

                        update_field('actor_gender', $gender_decoded, $post_id);
                    }

                    // zipcode
                    if ($zipcode = $amateur->plz) {
                        update_field('actor_zipcode', $zipcode, $post_id);
                    }

                    // link
                    if ($link = $amateur->url) {
                        update_field('actor_profile_url', $link, $post_id);
                    }

                    // groesse
                    if($size = $amateur->groesse) {
                        update_field('actor_size', $size/100 . 'm', $post_id);
                    }

                    // haare
                    if($haircolor = $amateur->haare) {
                        update_field('actor_haircolor', $haircolor, $post_id);
                    }

                    // intimrasur
                    update_field('actor_shave', ($amateur->rasintim == 1 ? __('Ja', 'amateurtheme') : __('Nein', 'amateurtheme')), $post_id);

                    // beruf
                    if($job = $amateur->beruf) {
                        update_field('actor_job', $job, $post_id);
                    }

                    // suche
                    if($suche = $amateur->suche) {
                        update_field('actor_search', implode(', ', $suche), $post_id);
                    }

                    // für
                    if($search_for = $amateur->interesse) {
                        update_field('actor_search', implode(', ', $search_for), $post_id);
                    }

                    // alter
                    if($age = $amateur->u_alter) {
                        update_field('actor_age', $age, $post_id);
                    }

                    // augenfarbe
                    if($eyecolor = $amateur->augen) {
                        update_field('actor_eyecolor', $eyecolor, $post_id);
                    }

                    // sternzeichen
                    if($star_sign = $amateur->sternzeichen) {
                        update_field('actor_star_sign', $star_sign, $post_id);
                    }

                    // gewicht
                    if($gewicht = $amateur->gewicht) {
                        update_field('actor_weight', $gewicht . 'kg', $post_id);
                    }

                    // körpchengroesse
                    if($kg = $amateur->k_umfang) {
                        update_field('actor_breast_size', $kg . ($amateur->k_schale ? $amateur->k_schale : ''), $post_id);
                    }

                    // familien status
                    if($relationship_status = $amateur->famst) {
                        update_field('actor_relationship_status', $relationship_status, $post_id);
                    }

                    // sex orientation
                    if($sex_orientation = $amateur->sexor) {
                        update_field('actor_sex_orientation', $sex_orientation, $post_id);
                    }

                    // erscheinungsbild
                    if($aussehen = $amateur->aussehen) {
                        update_field('actor_bodystyle', implode(', ', $aussehen), $post_id);
                    }

                    //update_field('actor_last_updated', time(), $post_id);
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

        at_error_log(print_r($results,true));

        at_error_log('Stoped cronjob (MDH, ' . $id . ')');

        at_write_api_log('mdh', $cron->name, 'Total: ' . $results['total'] . ', Imported: ' . $results['created'] . ' Skipped: ' . $results['skipped']);

        echo json_encode($results);
    }
}