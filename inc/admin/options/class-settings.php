<?php
/**
 * Admin Controller
 *
 * @author milosm
 * @contribute MM <milos.live>
 */

namespace MMSpecificationsTable\Admin\Options;
use MMSpecificationsTable\Admin\Admin;

defined('ABSPATH') || exit;

class Settings {
	/**
	 * Construct
	*/
	public static function init(){
		add_action( 'admin_init', array( __CLASS__, 'settings_page_init' ) );
	}

	public static function load_default_settings(){
		$defaults = array(
			'mmps_view_per_page' => 15,
			'mmps_wc_default_specs'	=>	'remove_if_specs_not_empty'
		);

		foreach( $defaults as $option => $value ) {
			add_option( $option, $value );
		}
	}

	/**
	 * Create settings fields
	 *
	 * @return void
	*/
	public static function settings_page_init(){
		register_setting(
            'mmps_options',
            'mmps_options',
            array( __CLASS__, 'sanitize' )
        );

		register_setting(
            'mmps_options',
            'mmps_tab_title',
            array( __CLASS__, 'sanitize' )
        );

		register_setting(
            'mmps_options',
            'mmps_view_per_page',
            array( __CLASS__, 'sanitize' )
        );

		register_setting(
            'mmps_options',
            'mmps_wc_default_specs',
            array( __CLASS__, 'sanitize' )
        );

		register_setting(
            'mmps_options',
            'mmps_disable_default_styles',
            array( __CLASS__, 'sanitize' )
        );
	}

	/**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
	 * @return mixed sanitized $input
     */
    public static function sanitize( $input ) {
        return $input;
    }

	/**
	 * Settings menu page HTML output
	*/
	public static function Page_HTML(){
		Admin::get_template( 'options/views/settings' );
	}
}