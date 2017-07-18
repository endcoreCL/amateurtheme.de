<?php
/**
 * Registrieren der Navigationen
 * 
 * @author		Christian Lang
 * @version		1.0
 * @category	navigation
 */

if ( ! function_exists('at_register_menu') ) {
	/**
	 * at_register_menu function.
	 *
	 */
	add_action('init', 'at_register_menu');
	function at_register_menu()
	{
		if (at_get_topbar()) {
			register_nav_menu('nav_topbar', __('Topbar', 'amateurtheme'));
		}

		register_nav_menu('nav_main', __('Hauptnavigation', 'amateurtheme'));

		if (at_header_structure() == '5-2-5') {
			register_nav_menu('nav_main_second', __('Hauptnavigation (2)', 'amateurtheme'));
		}
		
		register_nav_menu('nav_footer', __('Footer', 'amateurtheme'));
	}
}