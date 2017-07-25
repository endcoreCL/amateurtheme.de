<?php
/*
 * datingtheme Framework Functions Library - Dont touch this. Use Childthemes!
 * 
 * @author		Christian Lang
 * @version		1.0
 * @category	functions
 */
global $wpdb;
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

define( 'AT_LIBRARY', TEMPLATEPATH . '/library' );
define( 'AT_HELPER', TEMPLATEPATH . '/library/helper' );
define( 'AT_PLUGINS', TEMPLATEPATH . '/library/plugins' );
define( 'AT_IMPORT', TEMPLATEPATH . '/library/import' );

/*
 * Plugins
 */
if ( !is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
    require_once(AT_PLUGINS . '/acf/core/acf.php');
}
if ( !is_plugin_active( 'kirki/kirki.php' ) ) {
    require_once(AT_PLUGINS . '/kirki/core/kirki.php');
}

/* 
 * Add-Ons
 */
require_once(AT_PLUGINS . '/kirki/kirki-functions.php');
require_once(AT_PLUGINS . '/kirki/kirki-customizer.php');
require_once(AT_PLUGINS . '/acf/field-type-autocomplete/acf-field-type-autocomplete.php');
require_once(AT_PLUGINS . '/acf/field-type-code/acf-code_area.php');
require_once(AT_PLUGINS . '/acf/field-type-selector/acf-field_selector.php');
require_once(AT_PLUGINS . '/acf/acf-tooltip/acf-tooltip.php');
require_once(AT_PLUGINS . '/acf/acf-functions.php');
require_once(AT_PLUGINS . '/acf/export/_load.php');
require_once(AT_PLUGINS . '/tinymce/_load.php');
require_once(AT_PLUGINS . '/mobile-detect/Mobile_Detect.php');
require_once(AT_PLUGINS . '/updates/_load.php');
require_once(AT_PLUGINS . '/scss/_load.php');

/*
 * Framework Funktionen
 */
require_once(AT_LIBRARY . '/helper/_load.php');
require_once(AT_LIBRARY . '/navigation/_load.php');
require_once(AT_LIBRARY . '/widgets/_load.php');
require_once(AT_LIBRARY . '/video/_load.php');

/*
 * Import
 */
define('AT_CRON_TABLE', $wpdb->prefix . 'at_import_cronjobs');
require_once(AT_LIBRARY . '/import/_load.php');
?>