<?php
/**
 * Update API
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

require 'theme-update-checker.php';
$DTIOUpdateChecker = new ThemeUpdateChecker(
    'datingtheme',
    'http://www.datingtheme.io/updates/?action=get_metadata&slug=datingtheme'
);

/**
 * Remove theme from wp.org update check
 */
add_filter( 'http_request_args', 'at_remove_theme_from_wporg_request', 5, 2 );
function at_remove_theme_from_wporg_request( $r, $url ) {
    // If it's not a theme update request, bail.
    if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) ) {
        return $r;
    }

    // Decode the JSON response
    $themes = json_decode( $r['body']['themes'] );

    // Remove the active parent and child themes from the check
    $parent = get_option( 'template' );
    $child = get_option( 'stylesheet' );
    unset( $themes->themes->$parent );
    unset( $themes->themes->$child );

    // Encode the updated JSON response
    $r['body']['themes'] = json_encode( $themes );

    return $r;
}