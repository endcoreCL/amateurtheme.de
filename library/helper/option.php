<?php
/*
 * Verarbeitung der Theme Options
 * 
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */
 
/*
 * Optionen - Design - Allgemein
 */
if ( ! function_exists( 'at_get_logo' ) ) {
	/**
	 * at_get_logo function.
	 *
	 * @param  boolean $fallback (default: true)
	 * @param  boolean $desc (default: true)
	 * @return string
	 */
	function at_get_logo($fallback = true, $desc = true) {
		$logo = get_field('design_logo', 'option');
		$logo2x = get_field('design_logo2x', 'option');
		$name = (get_bloginfo('name') ? '<strong>' . get_bloginfo('name') . '</strong>' : '');
		$description = (get_bloginfo('description') ? ' <small>' . get_bloginfo('description') . '</small>' : '');

		if ($desc == false) {
			$description = '';
		}

		if (!$logo && true == $fallback) {
			return apply_filters('at_set_navigation_brand', $name . $description);
		}

		if (is_array($logo2x) && wp_is_mobile()) {
			return '<img src="' . $logo2x['url'] . '" width="' . $logo2x['width'] . '" height="' . $logo2x['height'] . '" alt="' . $logo2x['alt'] . '" class="img-responsive" />';
		}

		if (is_array($logo)) {
			return '<img src="' . $logo['url'] . '" width="' . $logo['width'] . '" height="' . $logo['height'] . '" alt="' . $logo['alt'] . '" class="img-responsive" />';
		}

		return false;
	}
}

if ( ! function_exists( 'at_get_favicon' ) ) {
	/**
	 * at_get_favicon function.
	 *
	 */
	add_action('wp_head', 'at_get_favicon');
	function at_get_favicon() {
		$favicon_ico = get_field('design_favicon', 'option');
		$favicon_touch = get_field('design_favicon_touch', 'option');

		if ($favicon_ico)
			echo '<link rel="shortcut icon" href="' . $favicon_ico['url'] . '" type="image/x-icon" />';

		if ($favicon_touch)
			echo '<link rel="apple-touch-icon" href="' . $favicon_touch['url'] . '" />';
	}
}

if ( ! function_exists('at_get_wrapper_id') ) {
	/**
	 * at_get_wrapper_id function.
	 *
	 * @return string
	 */
	function at_get_wrapper_id() {
		$setting = get_field('design_layout', 'option');

		if ($setting == 'fullwidth')
			return 'wrapper-fluid';

		if ($setting == 'tiles')
			return 'wrapper-tiles';

		return 'wrapper';
	}
}

if ( ! function_exists('at_get_section_layout') ) {
	/**
	 * at_get_section_layout function.
	 *
	 * @return string
	 */
	function at_get_section_layout($section) {
		$setting = get_field('design_' . $section . '_layout', 'option');

		if ($setting)
			return $setting;

		return 'boxed';
	}
}

if ( ! function_exists('at_get_section_layout_class') ) {
	/**
	 * at_get_section_layout_class function.
	 *
	 * @param  string $section
	 * @return string
	 */
	function at_get_section_layout_class($section) {
		$setting = get_field('design_' . $section . '_layout', 'option');
		$wrapper_id = at_get_wrapper_id();

		if ('nav' == $section && ($wrapper_id != 'wrapper-fluid' || at_get_section_layout('header') == 'boxed'))
			return 'wrapped';

		if ($setting == 'boxed' && $wrapper_id == 'wrapper-fluid')
			return 'wrapped';
	}
}
 
/*
* Optionen - Design - Topbar
*/
if ( ! function_exists( 'at_topbar_structure' ) ) {
	/**
	 * at_topbar_structure function.
	 *
	 * @return string
	 */
	function at_topbar_structure() {
		$setting = get_field('design_topbar_structure', 'option');

		if ($setting)
			return $setting;

		return 'tl_nr';
	}
}

if ( ! function_exists('at_get_topbar') ) {
	/**
	 * at_get_topbar function.
	 *
	 * @return boolean
	 */
	function at_get_topbar() {
		$setting = get_field('design_topbar_show', 'option');

		if ($setting)
			return true;
	}
}

/*
 * Optionen - Design - Header
 */
if ( ! function_exists('at_header_layout') ) {
	/**
	 * at_header_layout function.
	 *
	 * @return string
	 */
	function at_header_layout() {
		$setting = get_field('design_header_layout', 'option');

		if ($setting)
			return $setting;

		return 'boxed';
	}
}

if ( ! function_exists('at_header_structure') ) {
	/**
	 * at_header_structure function.
	 *
	 * @return string
	 */
	function at_header_structure() {
		$setting = get_field('design_header_structure', 'option');

		if ($setting)
			return $setting;

		return '12';
	}
}

if ( ! function_exists('at_header_sticky_nav') ) {
	/**
	 * at_header_sticky_nav function.
	 *
	 */
	add_filter('wp_footer', 'at_header_sticky_nav', 99);
	function at_header_sticky_nav() {
		$setting = get_field('design_nav_sticky', 'option');

		if ($setting)
			echo '<script>
			jQuery(document).ready(function() {
				jQuery("#navigation").affix({
					offset: {
						top: jQuery("#navigation").offset().top,
					}
				});
				
				jQuery("#navigation").on("affix.bs.affix", function () {
					jQuery("#header").addClass("pb50"); 
				});
				
				jQuery("#navigation").on("affix-top.bs.affix", function () {
					jQuery("#header").removeClass("pb50"); 
				});
				
				if ( jQuery("#navigation").hasClass("affix") ) {
					jQuery("#header").addClass("pb50"); 
				}
			});
			</script>';
	}
}

if ( ! function_exists('at_header_nav_searchform') ) {
	/**
	 * at_header_nav_searchform function.
	 *
	 * @return boolean
	 */
	function at_header_nav_searchform() {
		$desktop = get_field('design_nav_searchform', 'option');
		$mobile = get_field('design_nav_mobile_searchform', 'option');

		if (('1' == $desktop && '12' == at_header_structure()) || '1' == $mobile)
			return true;

		return false;
	}
}

/*
 * Optionen - Design - Teaser
 */
if ( ! function_exists('at_teaser_hide') ) {
	/**
	 * at_teaser_hide function.
	 *
	 * @return string
	 */
	function at_teaser_hide() {
		$setting = get_field('design_teaser_hide', 'option');

		if ($setting)
			return $setting;
	}
}

/*
 * Optionen - Blog - Allgemein
 */
if ( ! function_exists( 'at_get_sidebar' ) ) {
	/**
	 * at_get_sidebar function.
	 *
	 * @param  string $post_type (default: blog)
	 * @param  string $section
	 * @param  string $option (default: option)
	 * @return string
	 */
	function at_get_sidebar($post_type = 'blog', $section, $option = 'option') {
		$setting = get_field($post_type. '_' . $section . '_sidebar', $option);

		if(!is_array($setting) && ('' != $setting))
			return $setting;

		return 'right';
	}
}

if ( ! function_exists( 'at_get_sidebar_size' ) ) {
	/**
	 * at_get_sidebar_size function.
	 *
	 * @param  string $post_type (default: blog)
	 * @param  string $section
	 * @param  string $option (default: option)
	 * @return string
	 */
	function at_get_sidebar_size($post_type = 'blog', $section, $option = 'option') {
		$pos = at_get_sidebar($post_type, $section, $option);
		$setting = get_field($post_type . '_' . $section . '_sidebar_size', $option);

		if($setting) {
			$setting = explode('_', $setting);
		}

		$content = (is_array($setting) ? $setting[0] : '8');
		$sidebar = (is_array($setting) ? $setting[1] : '4');

		if('left' == $pos) {
			$content .= ' col-sm-push-' . ($setting[1] ? $setting[1] : '4');
			$sidebar .= ' col-sm-pull-' . ($setting[0] ? $setting[0] : '8');
		}

		$output['content'] = $content;
		$output['sidebar'] = $sidebar;

		return $output;
	}
}

if ( ! function_exists('at_get_post_layout') ) {
	/**
	 * at_get_post_layout function.
	 *
	 * @param  string $section
	 * @return string
	 */
	function at_get_post_layout($section) {
		$setting = get_field('blog_' . $section . '_post_layout', 'option');

		if ($setting)
			return $setting;

		return 'small';
	}
}

if ( ! function_exists('at_excerpt_length') ) {
	/**
	 * at_excerpt_length function.
	 *
	 */
	add_filter('excerpt_length', 'at_excerpt_length', 999);
	function at_excerpt_length($length) {
		$setting = get_field('blog_excerpt_length', 'option');

		if ($setting)
			return $setting;

		return 55;
	}
}

if ( ! function_exists('at_get_blog_meta_pos') ) {
	/**
	 * at_get_blog_meta_pos function.
	 *
	 * @param  string $pos (default: top)
	 * @return boolean
	 */
	function at_get_blog_meta_pos($pos = 'top') {
		$setting = get_field('blog_single_meta_pos', 'option');

		if(!is_array($setting) && ("" != $setting)) {
			if($setting == $pos || $setting == 'both') {
				return true;
			} else {
				return false;
			}
		}

		if($setting == '') {
			if($pos == 'top') {
				return true;
			}
		}

		return false;
	}
}

/*
 * Optionen - Design - Footer
 */
if ( ! function_exists('at_footer_structure') ) {
	/**
	 * at_footer_structure function.
	 *
	 * @return string
	 */
	function at_footer_structure() {
		$setting = get_field('design_footer_structure', 'option');

		if ($setting)
			return $setting;

		return 'tl_nr';
	}
}

if ( ! function_exists('at_footer_sticky') ) {
	/**
	 * at_footer_sticky function.
	 *
	 */
	add_filter('body_class', 'at_footer_sticky');
	function at_footer_sticky($classes) {
		$setting = get_field('design_footer_sticky', 'option');
		$footer_widgets = get_field('design_footer_widgets', 'option');

		if ('1' == $setting && '1' != $footer_widgets)
			$classes[] = 'sticky-footer';

		return $classes;
	}
}

/*
 * Optionen - Allgemein - Anpassen
 */
if ( ! function_exists('at_enqueue_custom_css') ) {
	/**
	 * at_enqueue_custom_css function.
	 *
	 */
	add_filter('wp_head', 'at_enqueue_custom_css', 99);
	function at_enqueue_custom_css() {
		$css = get_field('custom_css', 'option');

		if ($css != "")
			echo $css;
	}
}

if ( ! function_exists('at_enqueue_custom_js_header') ) {
	/**
	 * at_enqueue_custom_js_header function.
	 *
	 */
	add_filter('wp_head', 'at_enqueue_custom_js_header', 999);
	function at_enqueue_custom_js_header() {
		$js = get_field('custom_js_header', 'option');

		if ($js != "")
			echo $js;
	}
}

if ( ! function_exists('at_enqueue_custom_js_footer') ) {
	/**
	 * at_enqueue_custom_js_footer function.
	 *
	 */
	add_filter('wp_footer', 'at_enqueue_custom_js_footer', 999);
	function at_enqueue_custom_js_footer() {
		$js = get_field('custom_js_footer', 'option');

		if ($js != "")
			echo $js;
	}
}

/*
 * Optionen - Allgemein - Social Buttons
 */
if ( ! function_exists('at_get_social') ) {
	/**
	 * at_get_social function.
	 *
	 * @param  string $section
	 * @return string
	 */
	function at_get_social($section) {
		$setting = get_field('socialbuttons_' . $section . '_show', 'option');

		if ($setting)
			return $setting;

		return false;
	}
}

if ( ! function_exists('at_get_social_pos') ) {
	/**
	 * at_get_social_pos function.
	 *
	 * @param  string $section
	 * @return string
	 */
	function at_get_social_pos($section) {
		$setting = get_field('socialbuttons_' . $section . '_show_pos', 'option');

		if ($setting)
			return $setting;

		return false;
	}
}