<?php
/**
 * Product Specifications Install class
 *
 * @package MMSpecificationsTable
 * @since  0.4
 */

namespace MMSpecificationsTable;

defined('ABSPATH') || exit;

class Install
{
    public static function install() {
        if ( ! is_blog_installed() ) {
			return;
		}

		// Check if we are not already running this routine.
		if ( 'yes' === get_transient( 'mmspecs_installing' ) ) {
			return;
        }

        set_transient( 'mmspecs_installing', 'yes', MINUTE_IN_SECONDS * 10 );

        delete_transient( 'mmspecs_installing' );

        Admin\Options\Settings::load_default_settings();    
        flush_rewrite_rules();
        
        do_action('mmspecs_installed');
    }
}
