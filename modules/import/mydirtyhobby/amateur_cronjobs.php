<?php
/**
 * Loading mydirtyhobby amateur cronjob functions
 *
 * @author        Christian Lang
 * @version        1.0
 * @category    helper
 */

if ( ! function_exists( 'at_import_mdh_amateur_cronjob_initiate' ) ) {
    /**
     * at_import_mdh_amateur_cronjob_initiate function
     *
     */
    add_action( 'at_import_cronjob_edit', 'at_import_mdh_amateur_cronjob_initiate', 10, 3 );
    function at_import_mdh_amateur_cronjob_initiate ( $id, $field, $value )
    {
        if ( $field == 'scrape' ) {
            global $wpdb;

            $cron = $wpdb->get_row( 'SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id );

            if ( $cron ) {
                if ( $cron->network == 'mydirtyhobby' && $cron->type == 'user' ) {
                    if ( $value == '1' ) {
                        if ( ! wp_next_scheduled( 'at_import_mdh_scrape_videos_cronjob', array( $id ) ) ) {
                            wp_schedule_event( time(), 'daily', 'at_import_mdh_scrape_videos_cronjob', array( $id ) );
                        }
                    } else {
                        wp_clear_scheduled_hook( 'at_import_mdh_scrape_videos_cronjob', array( $id ) );
                    }
                }
            }
        }

        if ( $field == 'import' ) {
            global $wpdb;

            $cron = $wpdb->get_row( 'SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id );

            if ( $cron ) {
                if ( $cron->network == 'mydirtyhobby' && $cron->type == 'user' ) {
                    if ( $value == '1' ) {
                        if ( ! wp_next_scheduled( 'at_import_mdh_import_videos_cronjob', array( $id ) ) ) {
                            wp_schedule_event( time(), '30min', 'at_import_mdh_import_videos_cronjob', array( $id ) );
                        }
                    } else {
                        wp_clear_scheduled_hook( 'at_import_mdh_import_videos_cronjob', array( $id ) );
                    }
                }
            }
        }
    }
}

if ( ! function_exists( 'at_import_mdh_amateur_cronjob_delete' ) ) {
    /**
     * at_import_mdh_amateur_cronjob_delete function
     *
     */
    add_action( 'at_import_cronjob_delete', 'at_import_mdh_amateur_cronjob_delete', 10, 1 );
    function at_import_mdh_amateur_cronjob_delete ( $id )
    {
        global $wpdb;

        $cron = $wpdb->get_row( 'SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id );

        if ( $cron ) {
            if ( $cron->network == 'mydirtyhobby' && $cron->type == 'user' ) {
                wp_clear_scheduled_hook( 'at_import_mdh_import_videos_cronjob', array( $id ) );
                wp_clear_scheduled_hook( 'at_import_mdh_scrape_videos_cronjob', array( $id ) );
            }
        }
    }
}

if ( ! function_exists( 'at_import_mdh_scrape_videos_cronjob' ) ) {
    /**
     * at_import_mdh_scrape_videos_cronjob function
     *
     */
    add_action( 'at_import_mdh_scrape_videos_cronjob', 'at_import_mdh_scrape_videos_cronjob' );
    function at_import_mdh_scrape_videos_cronjob ( $id )
    {
        global $wpdb;
        $cron = $wpdb->get_row( 'SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id );

        at_error_log( print_r( $cron, true ) );

        if ( ! $cron ) {
            wp_clear_scheduled_hook( 'at_import_mdh_scrape_videos_cronjob', array( $id ) );
            at_error_log( 'Cron ' . $id . ' deleted' );
            exit;
        }

        $results = array( 'created' => 0, 'skipped' => 0, 'total' => 0, 'last_updated' => '' );

        if ( $cron ) {
            $u_id = $cron->object_id;

            // initiate import
            $import = new AT_Import_MDH_Crawler();

            // get total
            $args = array(
                'type'      => 'videos',
                'limit'     => 1,
                'amateurId' => $u_id
            );

            $total = $import->getTotal( $args );

            if ( $total ) {
                $wpdb->update(
                    AT_CRON_TABLE,
                    array(
                        'processing' => 1,
                    ),
                    array(
                        'id' => $id
                    )
                );

                $num_pages = ceil( $total / 100 );

                for ( $i = 0; $i <= $num_pages; $i++ ) {
                    $videos = $import->getAmateurVideos( $u_id, $i * 100 );
                    $data   = json_decode( $import->saveVideos( $videos, $u_id ), true );

                    if ( $data['created'] ) {
                        $results['created'] = $results['created'] + $data['created'];
                    }

                    if ( $data['skipped'] ) {
                        $results['skipped'] = $results['skipped'] + $data['skipped'];
                    }

                    if ( $data['total'] ) {
                        $results['total'] = $results['total'] + $data['total'];
                    }

                    $results['last_activity'] = date( "d.m.Y H:i:s" );
                }
            }

            $wpdb->update(
                AT_CRON_TABLE,
                array(
                    'created'       => $results['created'],
                    'skipped'       => $results['skipped'],
                    'processing'    => 0,
                    'last_activity' => date( "Y-m-d H:i:s" )
                ),
                array(
                    'object_id' => $u_id
                )
            );
        }

        echo json_encode( $results );
    }
}

if ( ! function_exists( 'at_import_mdh_import_videos_cronjob' ) ) {
    /**
     * at_import_mdh_import_videos_cronjob function
     *
     */
    add_action( 'at_import_mdh_import_videos_cronjob', 'at_import_mdh_import_videos_cronjob' );
    function at_import_mdh_import_videos_cronjob ( $id )
    {
        set_time_limit( 120 ); // try to set time limit to 120 seconds

        global $wpdb;
        $cron = $wpdb->get_row( 'SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id );

        if ( ! $cron ) {
            wp_clear_scheduled_hook( 'at_import_mdh_import_videos_cronjob', array( $id ) );
            at_error_log( 'Cron ' . $id . ' deleted' );
            exit;
        }

        $results = array( 'created' => 0, 'skipped' => 0, 'total' => 0, 'last_updated' => '' );

        at_error_log( 'Started cronjob (MDH, ' . $id . ')' );

        if ( $cron ) {
            $database = new AT_Import_MDH_DB();
            $videos   = $wpdb->get_results( 'SELECT * FROM ' . $database->table_videos . ' WHERE source_id = "' . $cron->object_id . '" AND imported != 1 LIMIT 0,50' );

            if ( $videos ) {
                foreach ( $videos as $item ) {
                    $video = new AT_Import_Video( $item->video_id );

                    if ( $video->unique ) {
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

                        $post_id = $video->insert( $item->title, $item->description );

                        if ( $post_id ) {
                            // fields
                            $fields = at_import_mdh_prepare_video_fields( $item->video_id );
                            if ( $fields ) {
                                $video->set_fields( $fields );
                            }

                            // thumbnail
                            if ( $item->preview ) {
                                $preview = json_decode( $item->preview );
                                $image   = $preview->censored;

                                if ( get_option( 'at_mdh_fsk18' ) == '1' ) {
                                    $image = $preview->normal;
                                }

                                if ( is_string( $image ) ) {
                                    $video->set_thumbnail( $image );
                                }
                            }

                            // category
                            $categories = json_decode( $item->categories );
                            if ( $categories ) {
                                foreach ( $categories as $cat ) {
                                    if ( $cat->name ) {
                                        $video->set_term( 'video_category', $cat->name );
                                    }
                                }
                            }

                            // tags
                            $tags = json_decode( $item->tags );
                            if ( $tags ) {
                                foreach ( $tags as $tag ) {
                                    $video->set_term( 'video_tags', $tag );
                                }
                            }

                            // actor
                            $video->set_term( 'video_actor', $item->object_name, 'mdh', $item->object_id );

                            $results['created']       += 1;
                            $results['total']         += 1;
                            $results['last_activity'] = date( "d.m.Y H:i:s" );

                            // update actor data if category import
                            if ( $cron->type == 'category' ) {
                                at_updateActor( $item->object_id );
                            }

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
                        $results['total']   += 1;

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

            // update actor data if user import
            if ( $cron->type == 'user' ) {
                at_updateActor( $cron );
            }

            $wpdb->update(
                AT_CRON_TABLE,
                array(
                    'processing'    => 0,
                    'last_activity' => date( "Y-m-d H:i:s" )
                ),
                array(
                    'object_id' => $cron->object_id
                )
            );
        }

        at_error_log( print_r( $results, true ) );

        at_error_log( 'Stoped cronjob (MDH, ' . $id . ')' );

        at_write_api_log( 'mdh', $cron->name, 'Total: ' . $results['total'] . ', Imported: ' . $results['created'] . ' Skipped: ' . $results['skipped'] );

        echo json_encode( $results );
    }
}