<?php
/**
 * Spec. table Settings template
 *
 * @author Milos Milenkovic <milos.live>
 * @package Wordpress
 * @subpackage Product Specifications Table Plugin
 * @link https://milos.live
 * @license GNU General Public License v2 or later, http://www.gnu.org/licenses/gpl-2.0.html
 * @version 0.1
*/ ?>

<div class="mmps-page">
	<div class="mmps-settings-wrap">
		<h3><?php _e('Product specifications general settings', 'product-specifications'); ?></h3>
		<p class="title-note"></p>

		<div class="mmps-box-wrapper mmps-box-padded clearfix">
			<div class="mmps-box-top clearfix">
				<h4><?php _e('settings', 'product-specifications'); ?></h4>
			</div><!-- .mmps-box-top -->

			<form method="post" action="options.php">
				<p class="mmps-field-wrap">
					<strong class="label"><?php _e('Specs Tab Title', 'product-specifications'); ?></strong>
					<em class="note"><?php _e('The title of product specs. tab in product single page', 'product-specifications'); ?></em>
					<input type="text" name="mmps_tab_title" value="<?php echo get_option('mmps_tab_title'); ?>">
				</p>

				<p class="mmps-field-wrap">
					<strong class="label"><?php _e('Views per page', 'product-specifications'); ?></strong>
					<em class="note"><?php _e('How many records should be seen in a page?', 'product-specifications'); ?></em>
					<input type="number" name="mmps_view_per_page" value="<?php echo get_option('mmps_view_per_page'); ?>">
				</p>

				<p class="mmps-field-wrap">
					<strong class="label"><?php _e('Woocommerce default specs. behaviour', 'product-specifications'); ?></strong>
					<em class="note"><?php _e('Choose what to do with woocommerce default specifications table', 'product-specifications'); ?></em>

					<select name="mmps_wc_default_specs">
						<option value="remove" <?php selected( 'remove', get_option('mmps_wc_default_specs') ); ?>><?php _e('Always Remove', 'product-specifications'); ?></option>
						<option value="remove_if_specs_not_empty" <?php selected( 'remove_if_specs_not_empty', get_option('mmps_wc_default_specs') ); ?>><?php _e('Remove if product has a specs. table', 'product-specifications'); ?></option>
						<option value="keep" <?php selected( 'keep', get_option('mmps_wc_default_specs') ); ?>><?php _e('Always keep', 'product-specifications'); ?></option>
					</select>
				</p>

				<p class="mmps-field-wrap">
					<strong class="label"><?php _e('Disable plugin\'s CSS styles', 'product-specifications'); ?></strong>
					<label>
						<input type="checkbox" name="mmps_disable_default_styles" <?php checked( get_option('mmps_disable_default_styles'), 'on' ); ?>>
						<?php _e('Disable plugin\'s CSS styles', 'product-specifications'); ?>
					</label>
				</p>

				<?php settings_fields( 'mmps_options' ); ?>
				<?php submit_button(); ?>
			</form>
		</div><!-- .mmps-box-wrapper -->
	</div><!-- .mm-specs-settings-wrap -->
</div><!-- .mmps-page -->