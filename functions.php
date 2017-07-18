<?php
/*
 * datingtheme Framework Functions Library - Dont touch this. Use Childthemes!
 * 
 * @author		Christian Lang
 * @version		1.0
 * @category	functions
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

define( 'at_LIBRARY', TEMPLATEPATH . '/library' );
define( 'at_HELPER', TEMPLATEPATH . '/library/helper' );
define( 'at_PLUGINS', TEMPLATEPATH . '/library/plugins' );

/*
 * Plugins
 */
if ( !is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
    require_once(at_PLUGINS . '/acf/core/acf.php');
}
if ( !is_plugin_active( 'kirki/kirki.php' ) ) {
    require_once(at_PLUGINS . '/kirki/core/kirki.php');
}

/* 
 * Add-Ons
 */
require_once(at_PLUGINS . '/kirki/kirki-functions.php');
require_once(at_PLUGINS . '/kirki/kirki-customizer.php');
require_once(at_PLUGINS . '/acf/field-type-autocomplete/acf-field-type-autocomplete.php');
require_once(at_PLUGINS . '/acf/field-type-code/acf-code_area.php');
require_once(at_PLUGINS . '/acf/field-type-selector/acf-field_selector.php');
require_once(at_PLUGINS . '/acf/acf-tooltip/acf-tooltip.php');
require_once(at_PLUGINS . '/acf/acf-functions.php');
require_once(at_PLUGINS . '/acf/export/_load.php');
require_once(at_PLUGINS . '/tinymce/_load.php');
require_once(at_PLUGINS . '/mobile-detect/Mobile_Detect.php');
require_once(at_PLUGINS . '/updates/_load.php');

/*
 * Framework Funktionen
 */
require_once(at_LIBRARY . '/helper/_load.php');
require_once(at_LIBRARY . '/navigation/_load.php');
require_once(at_LIBRARY . '/widgets/_load.php');
?>