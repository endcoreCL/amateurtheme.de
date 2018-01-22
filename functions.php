<?php
/**
 * xCORE Framework Functions Library - Don't touch this.
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	functions
 */
global $wpdb;

define( 'XCORE_LIBRARY', TEMPLATEPATH . '/library' );
define( 'XCORE_HELPER', TEMPLATEPATH . '/library/helper' );
define( 'XCORE_PLUGINS', TEMPLATEPATH . '/library/plugins' );
define( 'XCORE_MODULES', TEMPLATEPATH . '/modules' );
define( 'AT_CRON_TABLE', $wpdb->prefix . 'at_import_cronjobs' );

/**
 * Plugins
 */
require_once(XCORE_PLUGINS . '/scssphp/_load.php');
require_once(XCORE_PLUGINS . '/mobile-detect/Mobile_Detect.php');
require_once(XCORE_PLUGINS . '/acf/core/acf.php');
require_once(XCORE_PLUGINS . '/acf/field-type-code/acf-code-field.php');
require_once(XCORE_PLUGINS . '/jsonreader/autoload.php');

/**
 * Framework
 */
require_once(XCORE_LIBRARY . '/helper/_load.php');
require_once(XCORE_LIBRARY . '/navigation/_load.php');
require_once(XCORE_LIBRARY . '/widgets/_load.php');

/**
 * Modules
 */
require_once(XCORE_MODULES . '/video/_load.php');
require_once(XCORE_MODULES . '/import/_load.php');