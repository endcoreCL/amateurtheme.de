<?php
/**
 * ACF Functions
 *
 * @author        Christian Lang
 * @version        1.0
 * @category    helper
 */

define( 'ACFGFS_API_KEY', 'AIzaSyBOkk5G9qoAGJ21lNVVANdAqT0JmIrbHcI' );

add_filter( 'acf/settings/save_json', 'xcore_acf_json_save_point' );
function xcore_acf_json_save_point ( $path )
{
    // update path
    $path = AT_PLUGINS . '/acf/fields';

    // return
    return $path;
}

add_filter( 'acf/settings/load_json', 'xcore_acf_json_load_point' );
function xcore_acf_json_load_point ( $paths )
{
    // remove original path (optional)
    unset( $paths[0] );

    // append path
    $paths[] = AT_PLUGINS . '/acf/fields';

    // return
    return $paths;
}

if ( ! function_exists( 'at_acf_option_pages' ) ) {
    /**
     * Add acf option pages
     */
    add_action( 'init', 'at_acf_option_pages' );
    function at_acf_option_pages ()
    {
        if ( function_exists( 'acf_add_options_page' ) ) {
            acf_add_options_page( array(
                'page_title' => __( 'Framework', 'xcore' ),
                'menu_title' => 'Framework',
                'icon_url'   => 'dashicons-heart',
                'capability' => 'manage_options'
            ) );

            acf_add_options_sub_page( array(
                'title'  => __( 'Allgemein', 'xcore' ),
                'parent' => 'acf-options-framework'
            ) );

            acf_add_options_sub_page( array(
                'title'  => __( 'Design', 'xcore' ),
                'parent' => 'acf-options-framework'
            ) );

            acf_add_options_sub_page( array(
                'title'  => __( 'Blog', 'xcore' ),
                'parent' => 'acf-options-framework'
            ) );

            acf_add_options_sub_page( array(
                'title'  => __( 'Videos', 'xcore' ),
                'parent' => 'acf-options-framework'
            ) );
        }
    }
}