<?php
/**
 * Tools ( Import/export )
 *
 * @author Milos Milenkovic <milos.live>
 * @package Wordpress
 * @subpackage Product Specifications Table Plugin
 * @link https://milos.live
 * @license GNU General Public License v2 or later, http://www.gnu.org/licenses/gpl-2.0.html
 * @version 0.1
*/

global $wpdb;
$product_id = $wpdb->get_var(
	$wpdb->prepare(
		"SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s AND post_status = 'publish'", 
		'refrigerator-freezer-freezer-top-series-4-kdd74al204',
		'product'
	)
);

delete_transient('mmspecs_data_migrating');
?>

<div class="mmps-tools-wrap">
	<h3><?php _e('Import / Export tables and fields', 'product-specifications'); ?></h3>
	<p class="title-note"></p>

	<div class="mmps-box-wrapper mmps-tools-box clearfix">
		<h3><?php _e( 'Export product tables', 'product-specifications' ); ?></h3>
		<p><?php _e('You can choose the tables you want to export and hit the download button , a <code>JSON</code> file will be downloaded , you can impot that later or on another wordpress installation.', 'product-specifications'); ?></p>
		<div class="mmps-export-import-wrap">
			<div class="mmps-export-box">
				<h2><?php _e('Export Product Specifications data', 'product-specifications'); ?></h2>
				<p><?php _e('You can choose to export only tables data or also with product data', 'product-specifications'); ?></p>
				<form action="<?php echo admin_url('admin-ajax.php'); ?>" method="post">
					<p><label><input type="checkbox" name="include_products"> <?php _e('Include Products data (file size may increase)', 'product-specifications'); ?></label></p>
					
					<?php wp_nonce_field('mmspecs_nonce_export', 'mms_ex_nonce'); ?>
					<input type="hidden" name="action" value="mmspecs_export_data">
					<button class="button primary" type="submit"><?php _e('Download', 'product-specifications'); ?></button>
				</form>
			</div><!-- .mmps-export-box -->

			<div class="mmps-import-box">
				<h2><?php _e('Import Product Specifications data', 'product-specifications'); ?></h2>
				<p><?php _e('Import your <code>JSON</code> file here', 'product-specifications'); ?></p>
				<form id="mmps_import_data_form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post">
					<p><label><?php _e('Select JSON file', 'product-specifications'); ?></label><br><input type="file" name="file"></p>
					
					<?php wp_nonce_field('mmspecs_nonce_import', 'mms_im_nonce'); ?>
					<input type="hidden" name="action" value="mmspecs_import_data">
					<button class="button primary" type="submit"><?php _e('Import', 'product-specifications'); ?></button>
				</form>

				<div id="mmspecs_import_results"></div>
			</div><!-- .mmps-import-box -->
		</div>
	</div>
</div><!-- .mm-specs-tools-wrap -->