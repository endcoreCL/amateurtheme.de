<?php
/**
 * Hilfsfunktionen für die Navigation Navigationen
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	navigation
 */

add_action('init', 'xcore_register_menu');
function xcore_register_menu() {
    register_nav_menu('navigation_main', __('Hauptnavigation', 'xcore'));
}