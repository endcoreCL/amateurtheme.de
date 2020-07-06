<?php
/**
 * Loading mydirtyhobby import helper functions
 *
 * @author        Christian Lang
 * @version        1.0
 * @category    helper
 */

if ( ! function_exists( 'at_import_mdh_scripts' ) ) {
    /**
     * at_import_mdh_scripts function
     *
     */
    add_action( 'admin_enqueue_scripts', 'at_import_mdh_scripts' );
    function at_import_mdh_scripts ( $page )
    {
        if ( strpos( $page, 'at_import_mydirtyhobby' ) === false ) {
            return;
        }

        wp_enqueue_script( 'at-mydirtyhobby', get_template_directory_uri() . '/modules/import/_assets/js/mydirtyhobby.js' );
    }
}

if ( ! function_exists( 'at_import_mdh_get_video_count' ) ) {
    /**
     * at_import_mdh_get_video_count
     *
     * @param $source_id
     * @param bool $imported
     *
     * @return string
     */
    function at_import_mdh_get_video_count ( $source_id, $imported = false )
    {
        global $wpdb;

        $database = new AT_Import_MDH_DB();

        if ( $imported ) {
            $result = $wpdb->get_row( 'SELECT COUNT(source_id) as count FROM ' . $database->table_videos . ' WHERE source_id = "' . $source_id . '" AND imported = 1' );
        } else {
            $result = $wpdb->get_row( 'SELECT COUNT(source_id) as count FROM ' . $database->table_videos . ' WHERE source_id = "' . $source_id . '"' );
        }

        if ( $result ) {
            return $result->count;
        }

        return '0';
    }
}

if ( ! function_exists( 'at_import_mdh_prepare_video_fields' ) ) {
    /**
     * at_import_mdh_prepare_video_fields
     *
     * @param $video_id
     *
     * @return array|bool
     */
    function at_import_mdh_prepare_video_fields ( $video_id )
    {
        global $wpdb;
        $database = new AT_Import_MDH_DB();
        $video    = $wpdb->get_row( 'SELECT * FROM ' . $database->table_videos . ' WHERE video_id = ' . $video_id );
        if ( $video ) {
            // format duration
            $raw_duration = $video->duration;
            $duration     = gmdate( "H:i:s", str_replace( '.', '', $raw_duration ) );

            // format date
            $raw_date = $video->date;
            $date     = date( 'Ymd', strtotime( $raw_date ) );

            // format rating
            $raw_rating = $video->rating;
            $rating     = round( ( $raw_rating / 2 ) * 2 ) / 2;

            $fields = array(
                'duration'     => $duration,
                'views'        => 0,
                'date'         => $date,
                'rating'       => $rating,
                'rating_count' => $video->rating_count,
                'url'          => $video->link,
                'source'       => 'mdh',
                'language'     => $video->language,
                'unique_id'    => $video_id
            );

            return $fields;
        }

        return false;
    }
}

if ( ! function_exists( 'at_import_mdh_untag_video_as_imported' ) ) {
    /**
     * at_import_mdh_untag_video_as_imported
     */
    add_action( 'before_delete_post', 'at_import_mdh_untag_video_as_imported' );
    function at_import_mdh_untag_video_as_imported ( $post_id )
    {
        global $wpdb;

        global $post_type;
        if ( $post_type != 'video' ) {
            return;
        }

        $video_source = get_field( 'video_source', $post_id );
        if ( $video_source != 'mdh' ) {
            return;
        }

        $database = new AT_Import_MDH_DB();
        $video_id = get_post_meta( $post_id, 'video_unique_id', true );

        if ( $video_id ) {
            $wpdb->update(
                $database->table_videos,
                array(
                    'imported' => '0'
                ),
                array(
                    'video_id' => $video_id
                )
            );
        }
    }
}

/**
 * Add promo and campaign param for all mdh urls
 *
 * @param $value
 * @param $post_id
 * @param $field
 *
 * @return string
 */
add_filter( 'acf/load_value/name=video_url', 'xcore_mdh_promo_urls', 10, 3 );
function xcore_mdh_promo_urls ( $value, $post_id, $field )
{
    if ( get_field( 'video_source', $post_id ) == 'mdh' ) {
        $promo = get_option( 'at_mdh_video_promo' );
        if ( $promo ) {
            $value .= '&promo=' . $promo;
        }

        $campaign = xcore_mdh_get_campaign( $post_id );
        if ( $campaign ) {
            $value .= '&atc=' . $campaign;
        }
    }

    return $value;
}

/**
 * Get campaign for given post
 *
 * @param $post_id
 *
 * @return bool
 */
function xcore_mdh_get_campaign ( $post_id )
{
    // global campaign
    $campaign = get_option( 'at_mdh_video_campaign' );

    // actor campaign
    $terms = wp_get_post_terms( $post_id, 'video_actor' );
    if ( $terms ) {
        foreach ( $terms as $term ) {
            $actor_campaign = get_field( 'actor_mdh_video_campaign', $term );

            if ( $actor_campaign ) {
                $campaign .= '~' . $actor_campaign;

                break;
            }
        }
    }

    return $campaign;
}
