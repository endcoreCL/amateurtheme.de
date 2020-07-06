<?php
/**
 * AmateurTheme Framework Functions Library - Don't touch this.
 *
 * @author        Christian Lang
 * @version        1.0
 * @category    functions
 */

global $wpdb;

define( 'AT_VERSION', '1.3.1' );
define( 'AT_LIBRARY', TEMPLATEPATH . '/library' );
define( 'AT_HELPER', TEMPLATEPATH . '/library/helper' );
define( 'AT_PLUGINS', TEMPLATEPATH . '/library/plugins' );
define( 'AT_MODULES', TEMPLATEPATH . '/modules' );
define( 'AT_CRON_TABLE', $wpdb->prefix . 'at_import_cronjobs' );

require_once AT_LIBRARY . '/requirements/_load.php';

// check requirements
if ( xcore_loading_requirements() ) {
    /**
     * Plugins
     */
    require_once AT_PLUGINS . '/scssphp/_load.php';
    require_once AT_PLUGINS . '/acf/field-type-code/acf-code-field.php';
    require_once AT_PLUGINS . '/acf/field-type-google-font/acf-google_font_selector.php';
    require_once AT_PLUGINS . '/jsonreader/autoload.php';
    require_once AT_PLUGINS . '/updates/_load.php';

    /**
     * Framework
     */
    require_once AT_LIBRARY . '/helper/_load.php';
    require_once AT_LIBRARY . '/navigation/_load.php';
    require_once AT_LIBRARY . '/widgets/_load.php';

    /**
     * Modules
     */
    require_once AT_MODULES . '/video/_load.php';
    require_once AT_MODULES . '/import/_load.php';
}