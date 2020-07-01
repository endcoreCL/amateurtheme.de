<?php
/**
 * Loading mydirtyhobby amateur cronjob functions
 *
 * @author        Christian Lang
 * @version        1.0
 * @category    helper
 */

function at_get_top_video_cronjob ()
{
    $cronjobs = new AT_Import_Cron();
    $results  = $cronjobs->get( [ 'object_id' => 'top_video' ] );

    if ( $results ) {
        foreach ( $results as $result ) {
            return $result->id;
        }
    }

    return false;
}

/**
 * Register top video cronjobs
 */
add_action( 'admin_init', function () {
    $cronjobs   = new AT_Import_Cron();
    $cronjob_id = at_get_top_video_cronjob();

    if ( get_option( 'at_mdh_top_videos_cronjob' ) == '1' ) {
        if ( ! $cronjob_id ) {
            $cronjob_id = $cronjobs->add(
                [
                    'object_id' => 'top_video',
                    'name'      => 'Top Videos',
                    'alias'     => 'Top Videos',
                    'network'   => 'mydirtyhobby',
                    'type'      => 'top_video'
                ]
            );

            $cronjob_id = at_get_top_video_cronjob();
        }

        // register scrape cronjob
        if ( ! wp_next_scheduled( 'at_import_mdh_top_video_scrape' ) ) {
            wp_schedule_event( time(), '30min', 'at_import_mdh_top_video_scrape' );
        }

        // register import cronjob
        if ( ! wp_next_scheduled( 'at_import_mdh_import_videos_cronjob', array( $cronjob_id ) ) ) {
            wp_schedule_event( time(), 'hourly', 'at_import_mdh_import_videos_cronjob', array( $cronjob_id ) );
        }


    } else {
        // deregister scrape cronjob
        wp_clear_scheduled_hook( 'at_import_mdh_top_video_scrape' );

        // deregister import cronjob
        wp_clear_scheduled_hook( 'at_import_mdh_import_videos_cronjob', array( $cronjob_id ) );

        $cronjobs->delete( $cronjob_id );
    }
} );

if ( ! function_exists( 'at_import_mdh_top_video_scrape' ) ) {
    /**
     * at_import_mdh_top_video_scrape function
     *
     */
    add_action( 'at_import_mdh_top_video_scrape', 'at_import_mdh_top_video_scrape' );
    function at_import_mdh_top_video_scrape ()
    {
        set_time_limit( 120 ); // try to set time limit to 120 seconds

        at_error_log( 'Started cronjob (MDH, Top videos)' );

        $import   = new AT_Import_MDH_Crawler();
        $response = $import->getTopVideos();

        if ( $response ) {
            $import->saveVideos( $response, 'top_video' );
        }

        at_error_log( 'Stoped cronjob (MDH, Top videos)' );

        at_write_api_log( 'mdh', 'Top videos', ' Scraped . ' . count( $response ) . ' videos' );
    }
}