<?php
/**
 * Spec. table Groups template
 *
 * @author Milos Milenkovic <milos.live>
 * @package Wordpress
 * @subpackage Product Specifications Table Plugin
 * @link https://milos.live
 * @license GNU General Public License v2 or later, http://www.gnu.org/licenses/gpl-2.0.html
 * @version 0.1
*/

/** Search query **/
$search_query = false;
if( isset( $_GET['q'] ) && !empty( $_GET['q'] ) ) {
	$search_query = stripcslashes( strip_tags( $_GET['q'] ) );
}

/** records per page **/
$limit = intval( get_option('mmps_view_per_page') ) ?: 15;

// just to count number of records
$all_groups = get_terms( array(
	'taxonomy'   => 'spec-group',
	'hide_empty' => false,
	'search'	 => $search_query
) );

$total_pages = sizeof( $all_groups ) > $limit ? ceil( sizeof( $all_groups ) / $limit ) : 1;

if( isset( $_GET['paged'] ) && !empty( $_GET['paged'] ) )
	$paged = stripcslashes( strip_tags( $_GET['paged'] ) );
else
	$paged = 1;

$offset = ( $paged - 1 ) * $limit;

$groups = get_terms( array(
	'taxonomy'   => 'spec-group',
	'hide_empty' => false,
	'number'	 => $limit,
	'offset'	 => $offset,
	'search'	 => $search_query,
	'orderby'    => 'term_id',
	'order'		 => 'DESC'
) ); ?>

<div class="mmps-page">
	<div class="mmps-settings-wrap">
		<h3><?php _e('Attribute Groups', 'product-specifications'); ?></h3>
		<p class="title-note"></p>

		<div class="mmps-box-wrapper clearfix">
			<div class="mmps-box-top clearfix">
				<h4><?php _e('Attribute Groups', 'product-specifications'); ?></h4>
				<div class="mmps-group-searchform">
					<form action="<?php echo esc_url( mmspecs_current_page_url() ); ?>" method="get">
						<input type="text" name="q" value="<?php echo $search_query ?: ''; ?>" placeholder="<?php _e('Search...', 'product-specifications'); ?>">

						<?php
						if( !empty( $_GET ) ) {
							foreach( $_GET as $key => $val ){
								if( $key != 'q' ) echo "<input type=\"hidden\" name=\"$key\" value=\"$val\">";
							}
						} ?>
						<button type="submit"><i class="dashicons dashicons-search"></i></button>
					</form>
				</div><!-- .mmps-group-searchform -->
			</div><!-- .mmps-box-top -->

			<div id="mmps_table_wrap">
				<table class="mmps-table" id="mmps_table">
					<thead>
						<tr>
							<th class="check-column"><input type="checkbox" id="cb-select-all-1" class="selectall"></th>
							<th><?php _e('ID', 'product-specifications'); ?></th>
							<th><?php _e('Group name', 'product-specifications'); ?></th>
							<th><?php _e('Group slug', 'product-specifications'); ?></th>
							<th><?php _e('# of attributes', 'product-specifications'); ?></th>
							<th><?php _e('Actions', 'product-specifications'); ?></th>
						</tr>
					</thead>

					<tbody>
						<?php
						if( sizeof( $groups ) > 0 ) :
							foreach( $groups as $group ) :
								$attributes = mmspecs_get_attributes_by_group( $group->term_id ); ?>
							<tr>
								<td class="check-column"><input class="dlt-bulk-group" type="checkbox" name="slct_group[]" value="<?php echo $group->term_id; ?>"></td>
								<td><?php echo $group->term_id; ?></td>
								<td><h4><a href="#" class="edit" aria-label="<?php _e('Edit group', 'product-specifications'); ?>" data-mmpsmodal="true" data-type="group" data-action="edit" data-id="<?php echo $group->term_id; ?>"><?php echo $group->name; ?></a></h4></td>
								<td><?php echo rawurldecode( $group->slug ); ?></td>
								<td><?php echo sizeof( $attributes ); ?></td>
								<td class="mmps-actions">
									<a href="#" class="delete" data-type="group" data-id="<?php echo $group->term_id; ?>"><i class="dashicons dashicons-no"></i></a>
									<a href="#" role="button" class="edit" aria-label="<?php _e('Edit group', 'product-specifications'); ?>" data-mmpsmodal="true" data-type="group" data-action="edit" data-id="<?php echo $group->term_id; ?>"><i class="dashicons dashicons-welcome-write-blog"></i></a>

									<?php
									echo '<a class="view" href="'. add_query_arg( array( 'group_id' => $group->term_id, 'page' => 'mm-specs-attrs' ), esc_url( admin_url('admin.php') ) ) .'"><i class="dashicons dashicons-visibility"></i></a>'; ?>

									<a href="#" class="re-arange" data-id="<?php echo $group->term_id; ?>"><i class="dashicons dashicons-sort"></i></a>
								</td>
							</tr>
						<?php
							endforeach;
						else :
							echo '<tr><td class="not-found" colspan="5">' . __('Nothing found', 'product-specifications') . '</td></tr>';
						endif; ?>
					</tbody>
				</table>
			</div>
			<!-- #groups_table_wrap -->

			<div class="mmps-buttons clearfix">
				<a id="dpws_bulk_delete_btn" class="mmps-button red-btn delete-selecteds-btn" href="#" role="button" aria-label="<?php _e('delete Selected attributes', 'product-specifications'); ?>" disabled><?php _e('Delete Selected', 'product-specifications'); ?></a>

				<a data-mmpsmodal="true" id="dpws_add_new_btn" class="mmps-button add-new-btn" href="#" role="button" aria-label="<?php _e('Add a new group', 'product-specifications'); ?>"><?php _e('Add new', 'product-specifications'); ?></a>

				<div class="mmps-pagination">
					<?php echo paginate_links( array(
						'base'               => '%_%',
						'format'             => '?paged=%#%',
						'total'              => $total_pages,
						'current'            => isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 0,
						'show_all'           => false,
						'end_size'           => 4,
						'mid_size'           => 2,
						'prev_next'          => true,
						'prev_text'          => __('«', 'product-specifications'),
						'next_text'          => __('»', 'product-specifications'),
						'type'               => 'plain',
						'add_args'           => false,
						'add_fragment'       => '',
						'before_page_number' => '',
						'after_page_number'  => ''
					) ); ?>
				</div><!-- .pagination -->
			</div><!-- .mmps-buttons -->

		</div><!-- .mm-box-wrapper -->
	</div><!-- .mm-specs-settings-wrap -->
</div><!-- .mmps-page -->

<script id="mmps_delete_template" type="x-tmpl-mustache" data-templateType="JSON">
	{
		"data" : {
			"type" : "group"
		},
		"modal" : {
			"title"	: "<?php _e("Delete Attribute", "mmspecs"); ?>",
			"content" : "<?php _e("Are you sure you want to delete selected attribute(s)?", "mmspecs"); ?>",
			"buttons" : {
				"primary": {
					"value": "<?php _e("Yes", "mmspecs"); ?>",
					"href": "#",
					"className": "modal-btn btn-confirm btn-warn"
				},
				"secondary": {
					"value": "<?php _e("No", "mmspecs"); ?>",
					"className": "modal-btn btn-cancel",
					"href": "#",
					"closeOnClick": true
				}
			}
		}
	}
</script>

<script id="mmps_texts_template" type="x-tmpl-mustache">
	{
		"add_btn"     : "<?php _e('Add', 'product-specifications'); ?>",
		"edit_btn"    : "<?php _e('Update', 'product-specifications'); ?>",
		"add_title"   : "<?php _e('Add new group', 'product-specifications'); ?>",
		"edit_title"  : "<?php _e('Edit group', 'product-specifications'); ?>",
		"re_arrange"  : "<?php _e('Re arrange attributes', 'product-specifications'); ?>"
	}
</script>

<script id="modify_form_template" type="x-tmpl-mustache">
	<form action="#" method="post" id="mmps_modify_form">
		<p>
			<label for="group_name"><?php _e('Group name : ', 'product-specifications'); ?></label>
			<input type="text" name="group_name" value="" id="group_name" aria-required="true">
		</p>

		<p>
			<label for="group_slug"><?php _e('Group slug : ', 'product-specifications'); ?></label>
			<input type="text" name="group_slug" value="" id="group_slug" placeholder="<?php _e('Optional', 'product-specifications'); ?>">
		</p>

		<p>
			<label for="group_desc"><?php _e('Description : ', 'product-specifications'); ?></label>
			<textarea name="group_desc" id="group_desc" placeholder="<?php _e('Optional', 'product-specifications'); ?>"></textarea>
		</p>

		<input type="hidden" name="action" value="mmps_modify_groups">
		<input type="hidden" name="do" value="add">
		<?php wp_nonce_field( 'mmps_modify_groups', 'mmps_modify_groups_nonce' ); ?>
		<input type="submit" value="<?php _e('Add Group', 'product-specifications'); ?>" class="button button-primary">
	</form>
</script>