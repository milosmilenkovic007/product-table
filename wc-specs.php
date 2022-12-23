<?php
/**
 * Plugin Name:       Product table for WooCommerce
 * Plugin URI: 		  https://wwww.milos.live/
 * Description:       Plugin insert custom product specifications in table view
 * Version:           0.1.0
 * Author:            Milos Milenkovic
 * Author URI: 		  https://www.milos.live
 * License:           GPL-2.0+
 *
 * @link https://milos.live
*/

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'MMSPECS_PLUGIN_FILE' ) ) {
	define( 'MMSPECS_PLUGIN_FILE', __FILE__ );
}

/**
 * Load core packages and the autoloader.
 * The SPL Autoloader needs PHP 5.6.0+ and this plugin won't work on older versions
 */
if (version_compare(PHP_VERSION, '5.6.0', '>=')) {
	require __DIR__ . '/inc/class-autoloader.php';
}

/**
 * Returns the main instance of PDF Gen.
 *
 * @since  0.4
 * @return MMSpecificationsTable\App
 */
function mmspecs_table() {
	return MMSpecificationsTable\App::instance();
}

// Global for backwards compatibility.
$GLOBALS['mmspecs_table'] = mmspecs_table();
