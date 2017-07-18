<?php
/**
 * Diverse Filter
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

/**
 * jpeg quality 100%
 */
add_filter( 'jpeg_quality', create_function( '', 'return 100;' ) );

/**
 * remove style-tag from gallery-shortcode
 */
add_filter( 'use_default_gallery_style', '__return_false' );

if ( ! function_exists('at_filter_ptags_on_images') ) {
    /**
     * remove surrounding <p>-tag from images
     */
    add_filter('the_content', 'at_filter_ptags_on_images');
    function at_filter_ptags_on_images($content) {
        return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
    }
}

if ( ! function_exists('at_categories_count') ) {
    /**
     * add <span>-tag to wp_list_categories
     */
    add_filter('wp_list_categories', 'at_categories_count');
    function at_categories_count($links) {
        $links = str_replace('</a> (', '</a><span class="count">(', $links);
        $links = str_replace(')', ')</span>', $links);
        return $links;
    }
}

if ( ! function_exists('at_archive_count') ) {
    /**
     * add <span>-tag to get_archives_link
     */
    add_filter('get_archives_link', 'at_archive_count');
    function at_archive_count($links) {
        $links = str_replace('</a>&nbsp;(', '</a><span class="count">(', $links);
        $links = str_replace(')', ')</span>', $links);
        return $links;
    }
}

if ( ! function_exists('at_modify_post_mime_types') ) {
    /**
     * add pdf-filter to wp-media
     */
    add_filter('post_mime_types', 'at_modify_post_mime_types');
    function at_modify_post_mime_types($post_mime_types) {
        $post_mime_types['application/pdf'] = array(
            __('PDFs', 'amateurtheme'),
            __('PDFs Verwalten', 'amateurtheme'),
            _n_noop('PDF <span class="count">(%s)</span>',
                'PDFs <span class="count">(%s)</span>'));
        return $post_mime_types;
    }
}


if ( ! function_exists('at_browser_body_class') ) {
    /**
     * add body classses
     */
    add_filter('body_class', 'at_browser_body_class');
    function at_browser_body_class($classes) {
        global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
        if ($is_lynx) $classes[] = 'lynx';
        elseif ($is_gecko) $classes[] = 'gecko';
        elseif ($is_opera) $classes[] = 'opera';
        elseif ($is_NS4) $classes[] = 'ns4';
        elseif ($is_safari) $classes[] = 'safari';
        elseif ($is_chrome) $classes[] = 'chrome';
        elseif ($is_IE) {
            $classes[] = 'ie';
            if (preg_match('/MSIE ([0-9]+)([a-zA-Z0-9.]+)/', $_SERVER['HTTP_USER_AGENT'], $browser_version))
                $classes[] = 'ie' . $browser_version[1];
        } else $classes[] = 'unknown';
        if ($is_iphone) $classes[] = 'iphone';
        if (stristr($_SERVER['HTTP_USER_AGENT'], "android")) {
            $classes[] = 'android';
        } else if (stristr($_SERVER['HTTP_USER_AGENT'], "mac")) {
            $classes[] = 'osx';
        } elseif (stristr($_SERVER['HTTP_USER_AGENT'], "linux")) {
            $classes[] = 'linux';
        } elseif (stristr($_SERVER['HTTP_USER_AGENT'], "windows")) {
            $classes[] = 'windows';
        }

        return $classes;
    }
}

if ( ! function_exists('at_user_in_admin_body_class') ) {
    /**
     * add admin body class
     */
    add_filter('admin_body_class', 'at_user_in_admin_body_class');
    function at_user_in_admin_body_class($classes) {
        $current_user = wp_get_current_user();

        if ($current_user)
            $classes .= $current_user->user_login;

        return $classes;
    }
}

if ( ! function_exists('at_linked_images_class') ) {
    /**
     * add class to images in tinymce editor
     */
    add_filter('image_send_to_editor', 'at_linked_images_class', 10, 8);
    function at_linked_images_class($html, $id, $caption, $title, $align, $url, $size, $alt = '') {
        $classes = 'lightbox';

        if (preg_match('/<a.*? class=".*?">/', $html)) {
            $html = preg_replace('/(<a.*? class=".*?)(".*?>)/', '$1 ' . $classes . '$2', $html);
        } else {
            $html = preg_replace('/(<a.*?)>/', '$1 class="' . $classes . '" >', $html);
        }
        return $html;
    }
}

if ( ! function_exists('at_excerpt_more') ) {
    /**
     * add read more link to excerpt
     */
    add_filter('excerpt_more', 'at_excerpt_more');
    function at_excerpt_more($more) {
        global $post;
        return '… <a href="' . get_permalink($post->ID) . '">' . __('weiterlesen', 'amateurtheme') . '</a>';
    }
}