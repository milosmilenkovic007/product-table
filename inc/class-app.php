<?php
/**
 * Product Specifications main class
 *
 * @package MMSpecificationsTable
 * @since   0.4
 */

namespace MMSpecificationsTable;

defined('ABSPATH') || exit;

/**
 * MMSpecificationsTable main class
 */
final class App
{
	/**
	 * Plugin version.
	 *
	 * @var string
	 */
    public $version = '0.6.0';

    /**
     * Plugin instance.
     *
     * @since 0.1
     * @var null|MMSpecificationsTable
     */
    public static $instance = null;

    /**
     * Return the plugin instance.
     *
     * @return MMSpecificationsTable/App
     */
    public static function instance() {
        if ( ! self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * milosm_Pack constructor.
     */
    private function __construct() {		
        // Sync group attributes when an attribute gets updated.
		add_action( 'mmspecs_attributes_modified', array( $this, 'modified_attributes' ) );
		add_action('woocommerce_init', [$this, 'handle_woocommerce']);
		add_action('plugins_loaded', [$this, 'wc_needed_notice']);

        $this->define_constants();
        $this->init();
        $this->includes();
        $this->create_options();
    }

    /**
     * Include required files
     *
     */
    public function includes() {
        require_once( MMSPECS_ABSPATH . 'inc/functions-all.php' );
    }

    /**
     * Define constant if not already set.
     *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
    }

    /**
     * Define constants
     */
    public function define_constants() {
		$this->define('MMSPECS_ABSPATH', dirname(MMSPECS_PLUGIN_FILE) . '/');
		$this->define('MMSPECS_PLUGIN_BASENAME', plugin_basename(MMSPECS_PLUGIN_FILE));
		$this->define('MMSPECS_VERSION', $this->version);
		$this->define('MMSPECS_PLUGIN_URL', $this->plugin_url());
    }

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit(plugins_url('/', MMSPECS_PLUGIN_FILE));
    }

    /**
     * Do initial stuff
     */
    public function init() {
        // Install
        register_activation_hook(MMSPECS_PLUGIN_FILE, ['MMSpecificationsTable\\Install', 'install']);

        // Post types
        Post_Types::init();

		Admin\Admin::init();
		Shortcodes\Table::init();

        // Add scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'public_dependencies']);
    }

    /**
     * Register scripts and styles for public area
     */
    public function public_dependencies() {
		if( ! get_option('mmps_disable_default_styles') ){
			wp_enqueue_style( 'mmspecs-front-css', MMSPECS_PLUGIN_URL . '/assets/css/front-styles.css', array(), MMSPECS_VERSION );
		}
    }

    /**
     * Create Options as an stdClass
    */
    public function create_options(){
        $this->options = new \stdClass();
        $this->options->post_types = get_option('mmps_post_types');
        $this->options->per_page   = get_option('mmps_view_per_page');
	}
    
	/**
	 * Do initial stuff
	*/
	private function initialize(){
		// Building menus
		$top_menu = new mmspecs_options_page(
			array(
				'menu_title'    => __('Product table', 'product-specifications'),
				'page_title'    => __('Product specifications table', 'product-specifications'), // page title
				'id'            => 'mm-specs', // page id ( slug )
				'description'   => __('Product specifications table for E-Commerce websites', 'product-specifications'), // some description
				'order'         => 25, // menu order
				'parent'        => false, // parent settings page id
				'icon'          => '', // icon will be displayed if parent page is empty, Uses dashicons
				'redirect'		=> true, // redirect to first child-page if options are empty
				'capability'	=> 'edit_pages' // restrict access to certain users only
			)
		);
	}

	/**
	 * Check and add specifications table to woocommerce
	*/
	public function handle_woocommerce(){
		if(class_exists('WooCommerce')) {
			add_filter('woocommerce_product_tabs', array( $this, 'woocommerce_tabs' ));
		}
	}

	/**
	 * Add tables to woocommerce tabs
	 * Remove or keep old woocommerce tables ( based on plugin's settings )
	*/
	public function woocommerce_tabs( $tabs ){
		global $product;
		
		if( mmspecs_product_has_specs_table( $product->get_id() ) ){
			$tabs['mmspecs_product_specifications'] = array(
				'title' 	=> get_option('mmps_tab_title') ?: __( 'Product Specifications', 'product-specifications' ),
				'priority' 	=> 10,
				'callback' 	=> array( $this, 'woo_display_tab' )
			);
		}

		if( get_option('mmps_wc_default_specs') == 'remove' || ( mmspecs_product_has_specs_table( $product->get_id() ) && get_option('mmps_wc_default_specs') == 'remove_if_specs_not_empty' ) ) {

			unset( $tabs['additional_information'] );
		}

		return $tabs;
	}

	/**
	 * Display tab content Callback
	*/
	public function woo_display_tab(){
		echo do_shortcode('[specs-table]');
	}

	/**
	 * Trigger When an attribute is added or deleted and update group attribute ids
	 *
	 * @param   Array $args
	 * @return Array
	*/
	public function modified_attributes( $args ){
		// Sync. delete action with attribute groups
		if( $args['action'] == 'delete' ){
			for( $b = 0; $b < sizeof( $args['ids'] ); $b++ ){
				$attr_id 		  = $args['ids'][$b];
				$group_id 		  = get_term_meta( $attr_id, 'attr_group', true );
				$group_attributes = get_term_meta( $group_id, 'attributes', true );

				if( is_array( $group_attributes ) && sizeof( $group_attributes ) > 0 ){
					if( in_array( $attr_id, $group_attributes ) ){
						unset( $group_attributes[ $attr_id ] );
						$updated_attributes = array_diff( $group_attributes, array($attr_id) );
						update_term_meta( $group_id, 'attributes', $updated_attributes );
					}
				} else{
					delete_term_meta( $group_id, 'attributes' );
					add_term_meta( $group_id, 'attributes', array() );
				}
			}
		} elseif( $args['action'] == 'add' || $args['action'] == 'edit' ){
			for( $i = 0; $i < sizeof( $args['ids'] ); $i++ ){
				$attr_id 		  = $args['ids'][$i];
				$group_id 		  = get_term_meta( $attr_id, 'attr_group', true );
				$group_attributes = get_term_meta( $group_id, 'attributes', true );

				if( is_array( $group_attributes ) && sizeof( $group_attributes ) > 0 ){
					if( !in_array( $attr_id, $group_attributes ) ){
						array_push( $group_attributes, $attr_id  );
						update_term_meta( $group_id, 'attributes', $group_attributes );
					}
				} else{
					delete_term_meta( $group_id, 'attributes' );
					add_term_meta( $group_id, 'attributes', array( $attr_id ) );
				}
			}
		}

	}    

	public function wc_needed_notice() {
		if (! class_exists('WooCommerce')) {
			$this->add_notice('no_woo_notice', __('This plugin works properly with woocommerce, please install woocommerce first', 'product-specifications'), 'warning', 'forever');
		}
	
		add_action('wp_ajax_mm_dismiss_admin_notice', [$this, 'dismiss_alert']);

	}

	/**
	 * Add a notice to admin notices area
	 * 
	 * @param String   		$id  		A unique identifier
	 * @param String   		$message     Notice body
	 * @param String|Bool  	false - Not dismissable OR "close" - just close button OR "forever" - an option to permanently dismiss the notice
	 */
	public function add_notice($id, $message, $type = 'success', $dismiss = 'close') {
		if (! get_option('mm_notice_dismissed_' . $id)) {
			add_action( 'admin_notices', function() use($id, $message, $type, $dismiss){
				echo '<div class="notice notice-'. $type .' '. ($dismiss === 'forever' || $dismiss === 'close' ? 'is-dismissible' : '') .'"><p>';
				
				if ($dismiss == 'forever') {
					$message .= ' <a href="#" onClick="mmDismissAdminNotice(event, \''. $id .'\'); return false;">'.__('Dismiss', 'product-specifications').'</a>';
				}

				echo $message;
				echo '</p></div>';
			});
		}
	}

	public function dismiss_alert() {
		$id = isset($_POST['id']) ? trim(htmlspecialchars($_POST['id'])) : false;

		if ($id) {
			update_option('mm_notice_dismissed_' . $id, "1");
		}
	}
}
