<?php
/**
 * Loading big7 ajax functions
 *
 * @author        Christian Lang
 * @version        1.0
 * @category    helper
 */

if ( ! function_exists( 'at_import_big7_get_videos' ) ) {
    /**
     * at_import_big7_get_videos function
     *
     */
    add_action( 'wp_ajax_at_import_big7_get_videos', 'at_import_big7_get_videos' );
    function at_import_big7_get_videos ()
    {
        $uid = ( isset( $_GET['u_id'] ) ? $_GET['u_id'] : false );

        if ( ! $uid ) {
            echo json_encode( array( 'status', 'error' ) );
            exit;
        }

        $import   = new AT_Import_Big7_Crawler();
        $response = $import->getVideos( $uid );

        if ( $response ) {
            //at_debug($response);
            foreach ( $response as $k => $v ) {
                $v->imported = "false";

                $unique = at_import_check_if_video_exists( $v->video_id );

                if ( ! $unique ) {
                    $v->imported = "true";
                }

                $response[$k] = $v;
            }
        }

        echo json_encode( $response );
        exit;
    }
}

if ( ! function_exists( 'at_import_big7_amateurs_select' ) ) {
    /**
     * at_import_big7_amateurs_select function
     *
     */
    add_action( 'wp_ajax_at_big7_amateurs', 'at_import_big7_amateurs_select' );
    function at_import_big7_amateurs_select ()
    {
        global $wpdb;

        $database = new AT_Import_Big7_DB();

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
}