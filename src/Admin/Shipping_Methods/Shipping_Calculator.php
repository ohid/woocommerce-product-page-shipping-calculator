<?php
/**
 * Class Shipping_Method
 * 
 *
 * @package   Barn2\woocommerce-product-page-shipping-calculator
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */

namespace Barn2\Plugin\WooCommerce_Product_Page_Shipping_Calculator\Admin\Shipping_Methods;

use Barn2\WPPSC_Lib\Plugin\Simple_Plugin,
	Barn2\WPPSC_Lib\Registerable,
	Barn2\WPPSC_Lib\Service;

class Shipping_Calculator extends \WC_Shipping_Method {

	/**
	 * The main plugin instance
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Stores the URL to the WooCommerce -> Settings -> Shipping -> Shipping Options page
	 */
	private $shipping_options_url;

	/**
	 * Constructor
	 *
	 * @param  Simple_Plugin $plugin The main instance of this plugin
	 * @return void
	 */
    public function __construct(Simple_Plugin $plugin ) {
		$this->plugin = $plugin;
		$this->shipping_options_page_url();
		
		$this->id = 'wppsc-shipping-calculator';
        $this->title = __( 'Shipping Calculator', 'wppsc' );
        $this->method_title = __( 'Shipping calculator', 'wppsc' );
        $this->method_description = sprintf(
			__( 'The following options control the WooCommerce Product Page Shipping Calculator plugin. Enable/disable the calculator on the <a href="%s">Shipping options</a> page', 'wppsc' ),
			esc_url( $this->shipping_options_url )
		);
		// init the shipping calculator
		$this->init();
    }

	/**
	 * Init
	 * 
	 * @return void
	 */
	public function init() {
		$this->init_form_fields();
		// Save settings
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * Initialise settings form fields.
	 *
	 * Add an array of fields to be displayed on the shipping calculator's screen.
	 * @return void
	 */
	public function init_form_fields() {
		$license_field_description_html = $this->get_license_field_description();

		$this->form_fields = [
            'license_key' => [
                'title' => 'License Key',
                'type' => 'password',
                'label' => '',
                'default' => '',
				'description' => $license_field_description_html
            ],
            'shipping_calculator_position' => [
                'title' => 'Shipping Calculator Position',
                'type' => 'select',
                'label' => '',
                'default' => 'woocommerce_product_meta_start',
				'options' => array(
					'woocommerce_before_add_to_cart_form' => __( 'Before add to cart', 'wppsc' ),
					'woocommerce_after_add_to_cart_form' => __( 'After add to cart', 'wppsc' ),
					'woocommerce_product_meta_start' => __( 'Before product meta start', 'wppsc' ),
					'woocommerce_product_meta_end' => __( 'After product meta end', 'wppsc' ),
					'woocommerce_after_single_product_summary' => __( 'After single product summary', 'wppsc' ),
				),
				'description' => __( 'Select the position where you want to display the shipping calculator in product page', 'wppsc' )
            ],
        ];
	}

	/**
	 * Shipping option page URL
	 * 
	 * @return void
	 */
	public function shipping_options_page_url() {
		$this->shipping_options_url = add_query_arg(array(
			'page' => 'wc-settings',
			'tab' => 'shipping',
			'section' => 'options',
		), admin_url('admin.php') );
	}

	/**
	 * Get the HTML description for the license field
	 * 
	 * @return $output
	 */
	public function get_license_field_description() {
		$output = '<span class="wppsc-license-field-description">';
		$output .= '<button class="button">Check</button>';
		$output .= '<button class="button">Deactive</button>';
		$output .= '<span class="success">&#x2713; Your license key is active</span>';
		$output .= '</span>';

		return $output;
	}
}