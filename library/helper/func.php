<?php
/*
 * Diverse Hilfsfunktionen
 * 
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

/**
 * Thumbnail Support
 */
add_theme_support( 'post-thumbnails' );
add_image_size('at_large', 1140, 600, true);
add_image_size('at_large_9', 848, 446, true);
add_image_size('at_thumbnail', 360, 189, true);
add_image_size('at_thumbnail_9', 263, 138, true);

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

if ( ! function_exists('at_post_thumbnail') ) {
	/**
	 * at_post_thumbnail function.
	 *
	 * @param  boolean $post_id
	 * @param  string $size (default: thumbnail)
	 * @param  array $args
	 * @return string
	 */
	function at_post_thumbnail($post_id, $size = 'thumbnail', $args) {
		$output = '';

		if (isset($args['sidebar']) && ('right' == $args['sidebar'] || 'left' == $args['sidebar'])) {
			$size .= '_9';
		}

		if (has_post_thumbnail($post_id)) {
			$output = get_the_post_thumbnail($post_id, $size, $args);
		} else {
			if ('1' != get_field('blog_placeholders', 'option') && 'post' == get_post_type($post_id)) {
				return $output;
			}

			$sizes = at_get_image_sizes();

			$url = esc_url(get_template_directory_uri()) . '/_/img/placeholder-' . $sizes[$size]['width'] . 'x' . ($sizes[$size]['height'] != '0' ? $sizes[$size]['height'] : $sizes[$size]['width']) . '.jpg';

			$output = '<img src="' . apply_filters('at_post_thumbnail_placeholder', $url, $post_id, $size, $args) . '" width="' . $sizes[$size]['width'] . '" height="' . ($sizes[$size]['height'] != '0' ? $sizes[$size]['height'] : $sizes[$size]['width']) . '" alt="' . get_the_title($post_id) . '"';
			if (isset($args['class']))
				$output .= ' class="' . $args['class'] . '"';
			$output .= '/>';
		}

		return $output;
	}
}

if ( ! function_exists( 'at_get_image_sizes' ) ) {
	/**
	 * at_get_image_sizes function.
	 * @desc get all image size
	 *
	 * @param  string $size
	 * @return array
	 */
	function at_get_image_sizes($size = '') {
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

if ( ! function_exists('at_excerpt') ) {
	/**
	 * at_excerpt function.
	 *
	 * @param  int $limit
	 * @return string
	 */
	function at_excerpt($limit) {
		$excerpt = explode(' ', get_the_excerpt(), $limit);
		if (count($excerpt) >= $limit) {
			array_pop($excerpt);
			$excerpt = implode(" ", $excerpt) . '...';
		} else {
			$excerpt = implode(" ", $excerpt);
		}
		$excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);
		return $excerpt;
	}
}

if ( ! function_exists('at_get_image_sizes') ) {
	/**
	 * at_get_image_sizes function.
	 * @desc get all image size
	 *
	 * @param  string $size
	 * @return array
	 */
	function at_get_image_sizes($size = '') {
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

if ( ! function_exists('at_phone_detect') ) {
	/**
	 * at_phone_detect function.
	 *
	 * @return boolean
	 */
	function at_phone_detect() {
		$detect = new Mobile_Detect;
		if ($detect->isMobile() && !$detect->isTablet()) {
			return true;
		} else {
			return false;
		}
	}
}

if ( ! function_exists('at_tablet_detect') ) {
	/**
	 * at_tablet_detect function.
	 *
	 * @return boolean
	 */
	function at_tablet_detect() {
		$detect = new Mobile_Detect;

		if ($detect->isTablet()) {
			return true;
		} else {
			return false;
		}
	}
}

if ( ! function_exists('at_remove_recent_comment_style') ) {
	/**
	 * at_remove_recent_comment_style function.
	 *
	 */
	add_action('widgets_init', 'at_remove_recent_comment_style');
	function at_remove_recent_comment_style() {
		global $wp_widget_factory;
		remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
	}
}

if ( ! function_exists('at_ajaxurl') ) {
	/**
	 * at_ajaxurl function.
	 *
	 */
	add_action('wp_head', 'at_ajaxurl');
	function at_ajaxurl() {
		?>
		<script type="text/javascript">
			var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		</script>
		<?php
	}
}

/*
 * SVG in der Mediathek erlauben
 */
add_filter('upload_mimes', 'at_media_mime_types');
function at_media_mime_types($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
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