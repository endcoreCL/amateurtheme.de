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
            $date = $video->date;

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
            $wpdb->update( $database->table_videos, array(
                'imported' => '0'
            ), array(
                'video_id' => $video_id
            ) );
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

/**
 * Update Actor in Database with given id
 *
 * @param $actorId
 */
function at_updateActor ( $actorId )
{
    global $wpdb;

    // prevent object error
    if ( is_object( $actorId ) ) {
        $actorId = $actorId->object_id;
    }

    // check autor data
    $actorTermId = $wpdb->get_var( 'SELECT term_id FROM ' . $wpdb->prefix . 'termmeta WHERE meta_key = "actor_id" AND meta_value = "' . $actorId . '" LIMIT 0,1' );

    if ( ! $actorTermId ) {
        return;
    }

    $actorLastUpdated      = get_field( 'actor_last_updated', 'video_actor_' . $actorTermId );
    $actorLastUpdatedCheck = time() - strtotime( '-1 months' );

    if ( $actorLastUpdated >= $actorLastUpdatedCheck ) {
        return;
    }

    at_error_log( '### Actor check START ###' );
    at_error_log( $actorTermId );
    at_error_log( '### Actor check END ###' );

    $crawl   = new AT_Import_MDH_Crawler();
    $amateur = $crawl->getAmateurDetails( $actorId );

    if ( $amateur ) {
        $amateur = $amateur[0];

        at_error_log( print_r( $amateur, true ) );

        $post_id = 'video_actor_' . $actorTermId;

        // image
        $actor_image = get_field( 'actor_image', $post_id );
        if ( ! $actor_image ) {
            if ( $image = ( isset( $amateur->images->bild1 ) ? $amateur->images->bild1 : '' ) ) {
                $att_id = at_attach_external_image( $image, null, false, $amateur->nick . '-preview', array( 'post_title' => $amateur->nick ) );
                if ( $att_id ) {
                    update_field( 'actor_image', $att_id, $post_id );
                }
            }
        }

        // gender
        if ( $gender = $amateur->gender ) {
            if ( $gender == 'T' ) {
                $gender_decoded = __( 'Transexuell', 'amateurtheme' );
            } elseif ( $gender == 'F' ) {
                $gender_decoded = __( 'Weiblich', 'amateurtheme' );
            } else {
                $gender_decoded = __( 'Männlich', 'amateurtheme' );
            }

            update_field( 'actor_gender', $gender_decoded, $post_id );
        }

        // zipcode
        if ( $zipcode = $amateur->plz ) {
            update_field( 'actor_zipcode', $zipcode, $post_id );
        }

        // link
        if ( $link = $amateur->url ) {
            update_field( 'actor_profile_url', $link, $post_id );
        }

        // groesse
        if ( $size = $amateur->groesse ) {
            update_field( 'actor_size', $size / 100 . 'm', $post_id );
        }

        // haare
        if ( $haircolor = $amateur->haare ) {
            update_field( 'actor_haircolor', $haircolor, $post_id );
        }

        // intimrasur
        update_field( 'actor_shave', ( $amateur->rasintim == 1 ? __( 'Ja', 'amateurtheme' ) : __( 'Nein', 'amateurtheme' ) ), $post_id );

        // beruf
        if ( $job = $amateur->beruf ) {
            update_field( 'actor_job', $job, $post_id );
        }

        // suche
        if ( $suche = $amateur->suche ) {
            update_field( 'actor_search', implode( ', ', $suche ), $post_id );
        }

        // für
        if ( $search_for = $amateur->interesse ) {
            update_field( 'actor_search', implode( ', ', $search_for ), $post_id );
        }

        // alter
        if ( $age = $amateur->u_alter ) {
            update_field( 'actor_age', $age, $post_id );
        }

        // augenfarbe
        if ( $eyecolor = $amateur->augen ) {
            update_field( 'actor_eyecolor', $eyecolor, $post_id );
        }

        // sternzeichen
        if ( $star_sign = $amateur->sternzeichen ) {
            update_field( 'actor_star_sign', $star_sign, $post_id );
        }

        // gewicht
        if ( $gewicht = $amateur->gewicht ) {
            update_field( 'actor_weight', $gewicht . 'kg', $post_id );
        }

        // körpchengroesse
        if ( $kg = $amateur->k_umfang ) {
            update_field( 'actor_breast_size', $kg . ( $amateur->k_schale ? $amateur->k_schale : '' ), $post_id );
        }

        // familien status
        if ( $relationship_status = $amateur->famst ) {
            update_field( 'actor_relationship_status', $relationship_status, $post_id );
        }

        // sex orientation
        if ( $sex_orientation = $amateur->sexor ) {
            update_field( 'actor_sex_orientation', $sex_orientation, $post_id );
        }

        // erscheinungsbild
        if ( $aussehen = $amateur->aussehen ) {
            update_field( 'actor_bodystyle', implode( ', ', $aussehen ), $post_id );
        }

        update_field( 'actor_last_updated', time(), $post_id );
    }
}