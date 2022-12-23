<?php
/**
 * Attribute Groups Menu page
 *
 * @author milosm
 * @contribute MM <milos.live>
 */

namespace MMSpecificationsTable\Admin;

defined('ABSPATH') || exit;

class Attribute_Groups
{

	/**
	 * Menu page HTML output
	*/
	public static function Page_HTML(){
		Admin::get_template( 'views/groups' );
	}

}