<?php
/**
 * Loading mydirtyhobby ajax functions
 *
 * @author        Christian Lang
 * @version        1.0
 * @category    helper
 */

if ( ! function_exists( 'at_import_mdh_crawl_amateurs' ) ) {
    /**
     * at_import_mdh_crawl_amateurs function
     *
     */
    add_action( 'wp_ajax_at_import_mdh_crawl_amateurs', 'at_import_mdh_crawl_amateurs' );
    function at_import_mdh_crawl_amateurs ()
    {
        $offset   = ( isset( $_GET['offset'] ) ? $_GET['offset'] : '0' );
        $import   = new AT_Import_MDH_Crawler();
        $response = $import->crawlAmateurs( $offset );
        echo json_encode( $response );
        exit;
    }
}

if ( ! function_exists( 'at_import_mdh_get_videos' ) ) {
    /**
     * at_import_mdh_get_videos function
     *
     */
    add_action( 'wp_ajax_at_import_mdh_get_videos', 'at_import_mdh_get_videos' );
    function at_import_mdh_get_videos ()
    {
        $u_id = ( isset( $_GET['u_id'] ) ? $_GET['u_id'] : false );

        if ( ! $u_id ) {
            echo json_encode( array( 'status', 'error' ) );
            exit;
        }

        $import   = new AT_Import_MDH_Crawler();
        $response = $import->getAmateurVideos( $u_id );

        if ( $response ) {
            foreach ( $response as $item ) {
                $item->imported = "false";

                $unique = at_import_check_if_video_exists( $item->id );

                if ( ! $unique ) {
                    $item->imported = "true";
                }
            }
        }

        echo json_encode( $response );
        exit;
    }
}

if ( ! function_exists( 'at_import_mdh_get_top_videos' ) ) {
    /**
     * at_import_mdh_get_top_videos function
     *
     */
    add_action( 'wp_ajax_at_import_mdh_get_top_videos', 'at_import_mdh_get_top_videos' );
    function at_import_mdh_get_top_videos ()
    {
        $import   = new AT_Import_MDH_Crawler();
        $response = $import->getTopVideos();

        if ( $response ) {
            $import->saveVideos( $response );

            foreach ( $response as $item ) {
                $item->imported = "false";

                $unique = at_import_check_if_video_exists( $item->id );

                if ( ! $unique ) {
                    $item->imported = "true";
                }
            }
        }

        echo json_encode( $response );
        exit;
    }
}

if ( ! function_exists( 'at_mdh_import_video' ) ) {
    /**
     * at_mdh_import_video function
     *
     */
    add_action( "wp_ajax_at_mdh_import_video", "at_mdh_import_video" );
    function at_mdh_import_video ()
    {
        global $wpdb;

        $database       = new AT_Import_MDH_DB();
        $results        = '';
        $video_id       = $_POST['id'];
        $video_category = $_POST['video_category'];
        $video_actor    = $_POST['video_actor'];
        $video_actor_id = $_POST['video_actor_id'];

        $video = new AT_Import_Video( $video_id );

        if ( $video->unique ) {
            $item = $wpdb->get_row( 'SELECT * FROM ' . $database->table_videos . ' WHERE video_id = ' . $video_id );

            if ( $item ) {
                $post_id = $video->insert( $item->title, $item->description );

                if ( $post_id ) {
                    // fields
                    $fields = at_import_mdh_prepare_video_fields( $video_id );
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

                        if ( $image ) {
                            $video->set_thumbnail( $image );
                        }
                    }

                    // category
                    if ( $video_category != '-1' && $video_category != '' ) {
                        $video->set_term( 'video_category', $video_category );
                    } else {
                        $categories = json_decode( $item->categories );
                        if ( $categories ) {
                            foreach ( $categories as $cat ) {
                                if ( $cat->name ) {
                                    $video->set_term( 'video_category', $cat->name );
                                }
                            }
                        }
                    }

                    // actor
                    if ( $video_actor != '-1' && $video_actor != '' ) {
                        $video->set_term( 'video_actor', $video_actor, 'mdh', $video_actor_id );
                    } else {
                        $video->set_term( 'video_actor', $item->object_name, 'mdh', $item->object_id );
                    }

                    // update video item as imported
                    $wpdb->update(
                        $database->table_videos,
                        array(
                            'imported' => 1
                        ),
                        array(
                            'video_id' => $video_id
                        )
                    );
                }

                $results = $video_id;
            }
        } else {
            // video already exist, update video as imported
            $wpdb->update(
                $database->table_videos,
                array(
                    'imported' => 1
                ),
                array(
                    'video_id' => $video_id
                )
            );

            $results = 'error';
        }

        echo $results;

        die();
    }
}

if ( ! function_exists( 'at_import_mdh_get_category_videos' ) ) {
    /**
     * at_import_mdh_get_category_videos
     *
     */
    add_action( 'wp_ajax_at_import_mdh_get_category_videos', 'at_import_mdh_get_category_videos' );
    function at_import_mdh_get_category_videos ()
    {
        $c_id = ( isset( $_GET['c_id'] ) ? $_GET['c_id'] : false );

        if ( ! $c_id ) {
            echo json_encode( array( 'status', 'error' ) );
            exit;
        }

        $import   = new AT_Import_MDH_Crawler();
        $response = $import->getCategoryVideos( $c_id );

        if ( $response ) {
            foreach ( $response as $item ) {
                $item->imported = "false";

                $unique = at_import_check_if_video_exists( $item->video_id );

                if ( ! $unique ) {
                    $item->imported = "true";
                }
            }
        }

        echo json_encode( $response );
        exit;
    }
}

add_action( 'wp_ajax_at_mdh_amateurs', 'at_import_mdh_amateurs_select' );
function at_import_mdh_amateurs_select ()
{
    global $wpdb;

    $database = new AT_Import_MDH_DB();

    $q = ( isset( $_GET['q'] ) ? $_GET['q'] : false );

    if ( ! $q ) {
        die();
    }

    $limit = 500;

    $output = array();

    $items = $wpdb->get_results( 'SELECT * FROM ' . $database->table_amateurs . ' WHERE username LIKE "%' . $q . '%" LIMIT 0,' . $limit );

    if ( $items ) {
        foreach ( $items as $item ) {
            $output[] = array( $item->uid, $item->username );
        }
    }

    echo json_encode( $output );

    die();
}