<?php
/**
 * Attributes Menu page
 *
 * @author milosm
 * @contribute MM <milos.live>
 */

namespace MMSpecificationsTable\Admin;

defined('ABSPATH') || exit;

class Attributes
{

	/**
	 * Menu page HTML output
	*/
	public static function Page_HTML(){
		Admin::get_template( 'views/attributes' );
	}

}