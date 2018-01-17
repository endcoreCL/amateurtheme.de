<?php
/**
 * Diverse Hilfsfunktionen
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

/**
 * Theme supports
 */
add_theme_support('post-thumbnails');
add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));
add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
add_theme_support('customize-selective-refresh-widgets');

/**
 * Adding custom image sized
 */
add_image_size('xcore_xl', 1140, 0, false);
add_image_size('xcore_lg', 960, 0, false);
add_image_size('xcore_md', 720, 0, false);
add_image_size('xcore_sm', 540, 0, false);

if ( ! function_exists( 'xcore_get_image_sizes' ) ) {
    /**
     * Formatierte Ausgabe eines Arrays
     *
     * @param $var
     */
    function xcore_debug($var) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

if ( ! function_exists( 'xcore_get_image_sizes' ) ) {
    /**
     * Hilfsfunkion für aktuell angelegte Thumbnail Sizes
     *
     * @param string $size
     * @return array|bool|mixed
     */
    function xcore_get_image_sizes( $size = '' ) {
        global $_wp_additional_image_sizes;

        $sizes = array();
        $get_intermediate_image_sizes = get_intermediate_image_sizes();

        foreach ($get_intermediate_image_sizes as $_size) {
            if (in_array($_size, array('thumbnail', 'medium', 'large'))) {
                $sizes[$_size]['width'] = get_option($_size . '_size_w');
                $sizes[$_size]['height'] = get_option($_size . '_size_h');
                $sizes[$_size]['crop'] = (bool)get_option($_size . '_crop');
            } elseif (isset($_wp_additional_image_sizes[$_size])) {
                $sizes[$_size] = array(
                    'width' => $_wp_additional_image_sizes[$_size]['width'],
                    'height' => $_wp_additional_image_sizes[$_size]['height'],
                    'crop' => $_wp_additional_image_sizes[$_size]['crop']
                );
            }
        }

        if ($size) {
            if (isset($sizes[$size])) {
                return $sizes[$size];
            } else {
                return false;
            }
        }

        return $sizes;
    }
}

if ( ! function_exists( 'xcore_parse_external_url' ) ) {
    /**
     * Link-Attribute für externe / interne Links.
     *
     * @param $url
     * @param string $internal_class
     * @param string $external_class
     * @return array|bool
     */
    function xcore_parse_external_url($url, $internal_class = '', $external_class = '') {
        // Abort if parameter URL is empty
        if (empty($url)) {
            return false;
        }

        // Parse home URL and parameter URL
        $link_url = parse_url($url);
        $home_url = parse_url(home_url());

        // Decide on target
        if ($link_url['host'] == $home_url['host']) {
            // Is an internal link
            $target = '_self';
            $class = $internal_class;
            $rel = 'follow';

        } else {
            // Is an external link
            $target = '_blank';
            $class = $external_class;
            $rel = 'nofollow';
        }

        // Return array
        $output = array(
            'class' => $class,
            'target' => $target,
            'url' => $url,
            'rel' => $rel
        );
        return $output;
    }
}

if ( ! function_exists( 'xcore_phone_detect' ) ) {
    /**
     * Hilfsfunktion für die erkennung von Smartphones
     *
     * @return boolean
     */
    function xcore_phone_detect() {
        $detect = new Mobile_Detect;
        if ($detect->isMobile() && !$detect->isTablet()) {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists( 'xcore_tablet_detect' ) ) {
    /**
     * Hilfsfunktion für die erkennung von Tablets
     *
     * @return boolean
     */
    function xcore_tablet_detect() {
        $detect = new Mobile_Detect;

        if ($detect->isTablet()) {
            return true;
        } else {
            return false;
        }
    }
}