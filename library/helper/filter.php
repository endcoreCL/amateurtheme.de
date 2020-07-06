<?php
/**
 * WP Filter
 *
 * @author        Christian Lang
 * @version        1.0
 * @category    helper
 */


if ( ! function_exists( 'at_jpeg_quality' ) ) {
    /**
     * Set jpeg quality
     *
     * @param $quality
     *
     * @return int
     */
    add_filter( 'jpeg_quality', 'at_jpeg_quality' );
    function at_jpeg_quality ( $quality )
    {
        return 100;
    }
}

/**
 * Remove style tag from gallery
 */
add_filter( 'use_default_gallery_style', '__return_false' );

if ( ! function_exists( 'at_categories_count' ) ) {
    /**
     * Wrapping span for categories widget count
     *
     * @param $links
     *
     * @return mixed
     */
    add_filter( 'wp_list_categories', 'at_categories_count' );
    function at_categories_count ( $links )
    {
        $links = str_replace( '</a> (', '</a><span class="count">(', $links );
        $links = str_replace( ')', ')</span>', $links );

        return $links;
    }
}

if ( ! function_exists( 'at_archive_count' ) ) {
    /**
     *  Wrapping span for categories widget count
     *
     * @param $links
     *
     * @return mixed
     */
    add_filter( 'get_archives_link', 'at_archive_count' );
    function at_archive_count ( $links )
    {
        $links = str_replace( '</a>&nbsp;(', '</a><span class="count">(', $links );
        $links = str_replace( ')', ')</span>', $links );

        return $links;
    }
}

if ( ! function_exists( 'at_media_mime_types' ) ) {
    /**
     * Allow svgs in media
     *
     * @param $mimes
     *
     * @return mixed
     */
    add_filter( 'upload_mimes', 'at_media_mime_types' );
    function at_media_mime_types ( $mimes )
    {
        $mimes['svg'] = 'image/svg+xml';

        return $mimes;
    }
}

if ( ! function_exists( 'at_browser_body_class' ) ) {
    /**
     * Browser & OS Body-Classes
     */
    add_filter( 'body_class', 'at_browser_body_class' );
    function at_browser_body_class ( $classes )
    {
        global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
        if ( $is_lynx ) {
            $classes[] = 'lynx';
        } elseif ( $is_gecko ) {
            $classes[] = 'gecko';
        } elseif ( $is_opera ) {
            $classes[] = 'opera';
        } elseif ( $is_NS4 ) {
            $classes[] = 'ns4';
        } elseif ( $is_safari ) {
            $classes[] = 'safari';
        } elseif ( $is_chrome ) {
            $classes[] = 'chrome';
        } elseif ( $is_IE ) {
            $classes[] = 'ie';
            if ( preg_match( '/MSIE ([0-9]+)([a-zA-Z0-9.]+)/', $_SERVER['HTTP_USER_AGENT'], $browser_version ) ) {
                $classes[] = 'ie' . $browser_version[1];
            }
        } else {
            $classes[] = 'unknown';
        }
        if ( $is_iphone ) {
            $classes[] = 'iphone';
        }
        if ( stristr( $_SERVER['HTTP_USER_AGENT'], "android" ) ) {
            $classes[] = 'android';
        } elseif ( stristr( $_SERVER['HTTP_USER_AGENT'], "mac" ) ) {
            $classes[] = 'osx';
        } elseif ( stristr( $_SERVER['HTTP_USER_AGENT'], "linux" ) ) {
            $classes[] = 'linux';
        } elseif ( stristr( $_SERVER['HTTP_USER_AGENT'], "windows" ) ) {
            $classes[] = 'windows';
        }

        return $classes;
    }
}

if ( ! function_exists( 'at_user_in_admin_body_class' ) ) {
    /**
     * Add user to admin body classes
     *
     * @param $classes
     *
     * @return string
     */
    add_filter( 'admin_body_class', 'at_user_in_admin_body_class' );
    function at_user_in_admin_body_class ( $classes )
    {
        $current_user = wp_get_current_user();

        if ( $current_user ) {
            $classes .= ' ' . $current_user->user_login;
        }

        return $classes;
    }
}

if ( ! function_exists( 'at_search_in_terms_where' ) ) {
    /**
     * Extend search to check terms
     *
     * @param $where
     *
     * @return string
     */
    add_filter( 'posts_where', 'at_search_in_terms_where' );
    function at_search_in_terms_where ( $where )
    {
        global $wpdb;

        if ( ! is_admin() && is_search() ) {
            $where .= "OR (t.name LIKE '%" . get_search_query() . "%' AND {$wpdb->posts} . post_status = 'publish')";
        }

        return $where;
    }
}

if ( ! function_exists( 'at_search_in_terms_join' ) ) {
    /**
     * Extend search to check terms
     *
     * @param $join
     *
     * @return string
     */
    add_filter( 'posts_join', 'at_search_in_terms_join' );
    function at_search_in_terms_join ( $join )
    {
        global $wpdb;

        if ( ! is_admin() && is_search() ) {
            $join .= "LEFT JOIN {$wpdb->term_relationships} tr ON {$wpdb->posts}.ID = tr.object_id INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id";
        }

        return $join;
    }
}

if ( ! function_exists( 'at_search_in_terms_groupby' ) ) {
    /**
     * Extend search to check terms
     *
     * @param $groupby
     *
     * @return string
     */
    add_filter( 'posts_groupby', 'at_search_in_terms_groupby' );
    function at_search_in_terms_groupby ( $groupby )
    {
        global $wpdb;

        if ( ! is_admin() && is_search() ) {
            // we need to group on post ID
            $groupby_id = "{$wpdb->posts} . ID";
            if ( ! is_search() || strpos( $groupby, $groupby_id ) !== false ) {
                return $groupby;
            }

            // groupby was empty, use ours
            if ( ! strlen( trim( $groupby ) ) ) {
                return $groupby_id;
            }

            // wasn't empty, append ours
            return $groupby . ", " . $groupby_id;
        }

        return $groupby;
    }
}

if ( ! function_exists( 'at_search_defaults' ) ) {
    /**
     * Default posttypes for search
     *
     * @param $query
     *
     * @return mixed
     */
    add_filter( 'pre_get_posts', 'at_search_defaults' );
    function at_search_defaults ( $query )
    {
        if ( $query->is_search && ! is_admin() && ! isset( $_GET['post_type'] ) ) {
            $query->set( 'post_type', 'video' );
        }

        return $query;
    }
}

if ( ! function_exists( 'at_excerpt_length' ) ) {
    /**
     * Custom excerpt length
     *
     * @param $length
     *
     * @return mixed
     */
    add_filter( 'excerpt_length', 'at_excerpt_length', 999 );
    function at_excerpt_length ( $length )
    {
        $excerpt_length = get_field( 'blog_excerpt_length', 'options' );

        if ( $excerpt_length ) {
            return $excerpt_length;
        }

        return $length;
    }
}

if ( ! function_exists( 'xcore_media_mime_types_bugfix' ) ) {
    /**
     * Bugfix svg upload
     *
     * @param $checked
     * @param $file
     * @param $filename
     * @param $mimes
     *
     * @return array
     */
    add_filter( 'wp_check_filetype_and_ext', 'xcore_media_mime_types_bugfix', 10, 4 );
    function xcore_media_mime_types_bugfix ( $checked, $file, $filename, $mimes )
    {

        if ( ! $checked['type'] ) {
            $wp_filetype     = wp_check_filetype( $filename, $mimes );
            $ext             = $wp_filetype['ext'];
            $type            = $wp_filetype['type'];
            $proper_filename = $filename;

            if ( $type && 0 === strpos( $type, 'image/' ) && $ext !== 'svg' ) {
                $ext = $type = false;
            }

            $checked = compact( 'ext', 'type', 'proper_filename' );
        }

        return $checked;
    }
}

/**
 * Added noindex for all paginated pages
 */
add_filter( 'wpseo_robots', function ( $robots ) {
    if ( is_paged() ) {
        $robots = 'noindex, nofollow';
    }

    return $robots;
} );