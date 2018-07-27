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

/**
 * Memory Limit anpassen (Dashboard)
 */
ini_set( 'memory_limit', apply_filters( 'admin_memory_limit', -1) );

if ( ! function_exists('at_debug') ) {
    /**
     * at_debug function.
     *
     * @param  arraa $var
     * @return string
     */
    function at_debug($var) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

if ( ! function_exists('at_error_log') ) {
    /**
     * at_error_log function.
     *
     * @param  arraa $var
     * @return string
     */
    function at_error_log($var) {
        if(WP_DEBUG_LOG) {
            error_log($var);
        }
    }
}

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

if ( ! function_exists( 'at_attach_external_image' ) ) {
    /**
     * at_attach_external_image function.
     *
     * @param  string $url
     * @param  int $post_id
     * @param  string $thumb
     * @param  string $filename
     * @param  array $postdata
     * @return boolean
     */
    function at_attach_external_image($url = null, $post_id = null, $thumb = null, $filename = null, $post_data = array()) {
        if (!$url) return new WP_Error('missing', "Need a valid URL and post ID...");
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // Download file to temp location, returns full server path to temp file, ex; /home/user/public_html/mysite/wp-content/26192277_640.tmp
        $tmp = download_url($url);

        // If error storing temporarily, unlink
        if (is_wp_error($tmp)) {
            @unlink($file_array['tmp_name']);   // clean up
            $file_array['tmp_name'] = '';
            return $tmp; // output wp_error
        }

        preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $url, $matches);    // fix file filename for query strings

        // fix for empty file extension
        if (isset($matches[0])) {
            $url_filename = basename($matches[0]);
            $url_type = wp_check_filetype($url_filename);
        } else {
            $url_filename = $url;
            $url_type['ext'] = 'jpg';
        }

        // determine file type (ext and mime/type)

        // override filename if given, reconstruct server path
        if (!empty($filename)) {
            $filename = sanitize_file_name($filename);
            $tmppath = pathinfo($tmp);                                                        // extract path parts
            $new = $tmppath['dirname'] . "/" . $filename . "." . $tmppath['extension'];          // build new path
            rename($tmp, $new);                                                                 // renames temp file on server
            $tmp = $new;                                                                        // push new filename (in path) to be used in file array later
        }

        // assemble file data (should be built like $_FILES since wp_handle_sideload() will be using)
        $file_array['tmp_name'] = $tmp;                                                         // full server path to temp file

        if (!empty($filename)) {
            $file_array['name'] = $filename . "." . $url_type['ext'];                           // user given filename for title, add original URL extension
        } else {
            $file_array['name'] = $url_filename;                                                // just use original URL filename
        }

        // set additional wp_posts columns
        if (empty($post_data['post_title'])) {
            $post_data['post_title'] = basename($url_filename, "." . $url_type['ext']);         // just use the original filename (no extension)
        }

        if($post_id) {
            // make sure gets tied to parent
            if (empty($post_data['post_parent'])) {
                $post_data['post_parent'] = $post_id;
            }
        }

        // do the validation and storage stuff
        $att_id = media_handle_sideload($file_array, $post_id, null, $post_data);             // $post_data can override the items saved to wp_posts table, like post_mime_type, guid, post_parent, post_title, post_content, post_status

        // If error storing permanently, unlink
        if (is_wp_error($att_id)) {
            @unlink($file_array['tmp_name']);   // clean up
            return $att_id; // output wp_error
        }

        // set as post thumbnail if desired
        if ($thumb) {
            set_post_thumbnail($post_id, $att_id);
        }

        return $att_id;
    }
}

function at_attribute_array_html($attributes) {
	$attributes_html = '';

	if($attributes) {
		foreach($attributes as $k => $v) {
			if($v) {
				$attributes_html .= $k . '="' . implode($v, ' ') . '" ';
			}
		}
	}

	return $attributes_html;
}

function at_pb_render_editor($item, $count) {
	$class = $item['class'];
	$class_col = $item['class_col'];
	$bgimage = $item['bgimage'];
	$bgcolor = $item['bgcolor'];
	$editor = $item['editor'];

	$attributes = array(
		'class' => array(),
		'style' => array(),
	);

	if($class) {
		$attributes['class'][] = $class;
	}

	if(!$class_col) {
		$attributes['class'][] = 'col';
	} else {
		$attributes['class'][] = $class_col;
	}

	if($bgimage) {
		$attributes['style'][] = 'background-image: url(' . $bgimage['url'] . ');';
	}

	if($bgcolor) {
		$attributes['style'][] = 'background-color: ' . $bgcolor . ';';

		if($bgcolor == '#0c617a') {
			$attributes['class'][] = 'text-white';
		}
	}

	$output = '<div ' . at_attribute_array_html($attributes) . '>';
		$output .= $editor;
	$output .= '</div>';

	return $output;
}