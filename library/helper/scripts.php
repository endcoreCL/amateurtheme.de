<?php
/**
 * enqueue_scripts
 * 
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

require_once( AT_PLUGINS . '/scss/core/wp-sass.php' );

if ( ! function_exists('at_load_theme_scripts') ) {
	/**
	 * load frontend scripts
	 */
	add_action('wp_enqueue_scripts', 'at_load_theme_scripts');
	function at_load_theme_scripts() {
        /* Bootstrap CSS */
        wp_enqueue_style('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');

        /* CSS */
		if ('1' != get_field('scripte_fa', 'option')) {
			wp_enqueue_style('font-awesome', 'https://netdna.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
		}

        /* Google Fonts */
        wp_enqueue_style('open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,600');
        wp_enqueue_style('font-hind', 'https://fonts.googleapis.com/css?family=Hind:600');

        /* Base CSS */
        wp_enqueue_style('theme', get_template_directory_uri() . '/style.css');
        wp_enqueue_style('scss', get_stylesheet_directory_uri() . '/_/scss/style.scss');

        /* Bootstrap JS */
        if('1' == get_field('scripte_bootstrap_js', 'option')) {
            wp_enqueue_script('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array('jquery'), '3.3.7', true);
        }

        /* Custom Script */
        wp_enqueue_script('scripts', get_template_directory_uri().'/_/js/scripts.js', array('jquery', 'bootstrap'), '1.0', true);

		/* Comment Script */
		if (is_single() && !is_home() && comments_open()) {
			wp_enqueue_script('comment-reply');
		}

		/* Lightbox */
		if ('1' != get_field('scripte_lightbox', 'option')) {
			wp_enqueue_style('lightbox', get_template_directory_uri() . '/_/css/lightbox.css');
			wp_enqueue_script('lightbox', get_template_directory_uri() . '/_/js/lightbox.js', array('jquery', 'bootstrap'), '1.3.0', true);

			wp_localize_script('lightbox', 'lightbox_vars', array(
					'lightbox_tPrev' => __('Vorheriges Bild (Linke Pfeiltaste)', 'amateurtheme'),
					'lightbox_tNext' => __('Nächstes Bild (Rechte Pfeiltase)', 'amateurtheme'),
					'lightbox_tCounter' => __('%curr% von %total%', 'amateurtheme'),
				)
			);
		}

        /* Deregister Open-Sans */
        wp_deregister_style('open-sans');
	}
}

if ( ! function_exists('at_load_backend_styles') ) {
	/**
	 * load backend styles
	 */
	add_action('admin_enqueue_scripts', 'at_load_backend_styles');
	function at_load_backend_styles() {
		wp_enqueue_style('xcore-backend', get_template_directory_uri() . '/_/css/backend.css');

	}
}

if ( ! function_exists('at_backend_editor_styles') ) {
	/**
	 * load backend editor styles
	 */
	add_action('admin_init', 'at_backend_editor_styles');
	function at_backend_editor_styles() {
		add_editor_style(get_template_directory_uri() . '/_/css/editor.css');
	}
}

if ( ! function_exists('at_wp_disable_srcset') ) {
    /**
     * function at_wp_disable_srcset
     */
    add_action('wp_calculate_image_srcset', 'at_wp_disable_srcset');
    function at_wp_disable_srcset($sources) {
        $resp_images = get_field('scripte_wp_responsive_images', 'option');

        if($resp_images == '1') {
            return false;
        }

        return $sources;
    }
}




