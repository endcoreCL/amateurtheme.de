<?php
/**
 * Diverse WP Filter
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

/**
 * Remove unused image sizes
 */
add_filter( 'intermediate_image_sizes_advanced', 'xcore_remove_core_image_sized' );
function xcore_remove_core_image_sized( $sizes ){
    unset( $sizes[ 'medium_large' ] );
    return $sizes;
}

/**
 * Add own sized to Media Library
 */
add_filter( 'image_size_names_choose', 'xcore_add_own_image_sizes_to_media' );
function xcore_add_own_image_sizes_to_media( $sizes ) {
    return array_merge( $sizes,
        array(
            'xcore_xl' => __( 'xCORE XL (1140px)', 'xcore' ),
            'xcore_lg' => __( 'xCORE LG (960px)', 'xcore' ),
            'xcore_md' => __( 'xCORE MD (720px)', 'xcore' ),
            'xcore_sm' => __( 'xCORE SM (540px)', 'xcore' ),
        )
    );
}

/**
 * JPEG Quality 100%
 */
add_filter( 'jpeg_quality', create_function( '', 'return 100;' ) );

/**
 * E-Mail Benachrichtigung von Updates deaktvieren
 */
add_filter( 'auto_core_update_send_email', '__return_false' );

/**
 * Entferne style-Tag vom Gallery Shortcode
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Span um Widget Counter (Kategorie / Archiv Widget)
 */
add_filter('wp_list_categories', 'xcore_categories_count');
function xcore_categories_count($links) {
    $links = str_replace('</a> (', '</a><span class="count">(', $links);
    $links = str_replace(')', ')</span>', $links);
    return $links;
}

add_filter('get_archives_link', 'xcore_archive_count');
function xcore_archive_count($links) {
    $links = str_replace('</a>&nbsp;(', '</a><span class="count">(', $links);
    $links = str_replace(')', ')</span>', $links);
    return $links;
}

/**
 * PDF-Filter in der Mediathek
 */
add_filter( 'post_mime_types', 'modify_post_mime_types' );
function modify_post_mime_types( $post_mime_types ) {
    $post_mime_types['application/pdf'] = array(
        __( 'PDFs', 'xcore' ),
        __( 'PDFs Verwalten', 'xcore' ),
        _n_noop( 'PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>' ) );
    return $post_mime_types;
}

/**
 * SVG in der Mediathek erlauben
 */
add_filter('upload_mimes', 'xcore_media_mime_types');
function xcore_media_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}

/**
 * Browser & OS Body-Classes
 */
add_filter('body_class', 'xcore_browser_body_class');
function xcore_browser_body_class($classes) {
    global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
    if($is_lynx) $classes[] = 'lynx';
    elseif($is_gecko) $classes[] = 'gecko';
    elseif($is_opera) $classes[] = 'opera';
    elseif($is_NS4) $classes[] = 'ns4';
    elseif($is_safari) $classes[] = 'safari';
    elseif($is_chrome) $classes[] = 'chrome';
    elseif($is_IE) {
        $classes[] = 'ie';
        if(preg_match('/MSIE ([0-9]+)([a-zA-Z0-9.]+)/', $_SERVER['HTTP_USER_AGENT'], $browser_version))
            $classes[] = 'ie'.$browser_version[1];
    } else $classes[] = 'unknown';
    if($is_iphone) $classes[] = 'iphone';
    if ( stristr( $_SERVER['HTTP_USER_AGENT'],"android") ) {
        $classes[] = 'android';
    } else if ( stristr( $_SERVER['HTTP_USER_AGENT'],"mac") ) {
        $classes[] = 'osx';
    } elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"linux") ) {
        $classes[] = 'linux';
    } elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"windows") ) {
        $classes[] = 'windows';
    }

    if(xcore_phone_detect()) {
        $classes[] = 'is-phone';
    }

    if(xcore_tablet_detect()) {
        $classes[] = 'is-tablet';
    }

    if(!xcore_phone_detect() && !xcore_tablet_detect()) {
        $classes[] = 'is-desktop';
    }

    return $classes;
}

/**
 * User Admin Body Class
 */
add_filter('admin_body_class', 'xcore_user_in_admin_body_class');
function xcore_user_in_admin_body_class($classes) {
    $current_user = wp_get_current_user();

    if($current_user)
        $classes .= ' ' . $current_user->user_login;

    return $classes;
}

/**
 * Extra Klasse für angelegte Bilder im TinyMCE
 */
add_filter('image_send_to_editor','xcore_linked_images_class',10,8);
function xcore_linked_images_class($html, $id, $caption, $title, $align, $url, $size, $alt = '' ){
    // image links
    $pattern = "/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
    $replacement = '<a$1href=$2$3.$4$5 rel="lightbox" $6>';
    $html = preg_replace($pattern, $replacement, $html);

    return $html;
}

/**
 * HTML in Kommentaren deaktvieren
 */
add_filter( 'preprocess_comment', 'xcore_rmhtml_comment_post', '', 1 );
add_filter( 'comment_text', 'xcore_rmhtml_comment_display', '', 1 );
add_filter( 'comment_text_rss', 'xcore_rmhtml_comment_display', '', 1 );
add_filter( 'comment_excerpt', 'xcore_rmhtml_comment_display', '', 1 );
remove_filter( 'comment_text', 'make_clickable', 9 );
function xcore_rmhtml_comment_post( $incoming_comment ) {
    $incoming_comment['comment_content'] = htmlspecialchars($incoming_comment['comment_content']);
    $incoming_comment['comment_content'] = str_replace( "'", '&apos;', $incoming_comment['comment_content'] );
    return( $incoming_comment );
}
function xcore_rmhtml_comment_display( $comment_to_display ) {
    $comment_to_display = str_replace( '&apos;', "'", $comment_to_display );
    return $comment_to_display;
}