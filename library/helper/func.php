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

if ( ! function_exists('at_set_network') ) {
	/**
	 * at_set_network function.
	 *
	 */
	add_action('init', 'at_set_network');
	function at_set_network() {
		/*if (!isset($_COOKIE['curr_network'])) {
			$networks = get_field('form_networks', 'options');
			$prob = array();
			$networks_prob = array();
			$random = mt_rand(0, 1000);
			$offset = 0;

			$networks_prob['pornme'] = get_field('form_probability_pornme', 'option');
			$networks_prob['lustagenten'] = get_field('form_probability_lustagenten', 'option');

			if ($networks) {
				foreach ($networks as $network) {
					if ($networks_prob[$network] != "0" && $networks_prob[$network] != "")
						$prob[$network] = $networks_prob[$network] / 100;
				}
			}

			foreach ($prob as $key => $probability) {
				$offset += $probability * 1000;
				if ($random <= $offset) {
					@setcookie("curr_network", $key, time() + (3600 * 12));
					define('CURRENT_PORTAL', $key);
					return $key;
				}
			}
		} else {
			define('CURRENT_PORTAL', $_COOKIE['curr_network']);
		}*/

		define(CURRENT_PORTAL, 'lustagenten');
	}
}

if ( ! function_exists('at_get_network') ) {
	/**
	 * at_get_network function.
	 *
	 */
	function at_get_network() {
		return CURRENT_PORTAL;
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