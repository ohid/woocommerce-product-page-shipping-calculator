<?php
/**
 * Class Product_Page_Calculator
 *
 * @package   Barn2\woocommerce-product-page-shipping-calculator
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */

namespace Barn2\Plugin\WooCommerce_Product_Page_Shipping_Calculator;

use Barn2\WPPSC_Lib\Plugin\Simple_Plugin,
	Barn2\WPPSC_Lib\Registerable,
	Barn2\WPPSC_Lib\Service;

class Product_Page_Calculator implements Registerable, Service {

	/**
	 * The main plugin instance
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Determine if the calculator should display on the product page or not
	 *
	 * @var $calculator_visibility
	 */
	private $calculator_visibility;

	/**
	 * Constructor
	 *
	 * @param  Simple_Plugin $plugin The main instance of this plugin.
	 * @return void
	 */
	public function __construct( Simple_Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Register shpping methods

	 * @return void
	 */
	public function register() {
		$this->shipping_calculator_visibility();
		$position = $this->shipping_calculator_position();
		add_action( $position, array( $this, 'product_page_calculator' ) );
		add_shortcode( 'wppsc_shipping_calculator', array( $this, 'product_page_calculator' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
		add_action( 'wp_ajax_wppsc_calculate_shipping', array( $this, 'calculate_shipping' ) );
		add_action( 'wp_ajax_nopriv_wppsc_calculate_shipping', array( $this, 'calculate_shipping' ) );
	}

	/**
	 * The product page colculator
	 */
	public function product_page_calculator() {
		global $product;
		// return if the calculator should not show in product page.
		if ( $this->calculator_visibility === 'no' ) {
			return;
		}
		// get the plugin path.
		$path = $this->plugin->get_dir_path();
		// include the shipping calculator template.
		include $path . 'templates/cart/shipping-calculator.php';
	}

	/**
	 * Enqueue assets
	 *
	 * @return void
	 */
	public function assets() {
		// do not load the scripts if not on the product pages.
		if ( ! is_product() ) {
			return;
		}

		$this->load_styles();
		$this->load_scripts();
	}

	/**
	 * Enqueue the necessary styles
	 */
	public function load_styles() {
		wp_enqueue_style( 'wppsc-select2', plugin_dir_url( $this->plugin->get_file() ) . 'assets/css/select2.css', array(), $this->plugin->get_version() );

		wp_enqueue_style( 'wppsc-style', plugin_dir_url( $this->plugin->get_file() ) . 'assets/css/style.css', array(), $this->plugin->get_version() );
	}

	/**
	 * Enqueue the necessary scripts
	 */
	public function load_scripts() {
		wp_enqueue_script( 'wppsc-select2', plugin_dir_url( $this->plugin->get_file() ) . 'assets/js/select2.full.js', array( 'jquery' ), $this->plugin->get_version(), true );

		wp_enqueue_script( 'wppsc-main', plugin_dir_url( $this->plugin->get_file() ) . 'assets/js/main.js', array( 'jquery' ), $this->plugin->get_version(), true );

		wp_localize_script(
			'wppsc-main',
			'wppsc_script',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'wppsc-nonce' ),
			)
		);
	}

	/**
	 * Calculate shipping price on AJAX request
	 */
	public function calculate_shipping() {
		// check security.
		check_ajax_referer( 'wppsc_shipping_calculator', 'wppsc_shipping_calculator_nonce' );

		$output = array();
		\WC_Shortcode_Cart::calculate_shipping();
		WC()->cart->calculate_totals();

		if ( WC()->cart->get_cart_contents_count() === 0 ) {
			$blank_package = $this->get_shipping_packages();
			WC()->shipping()->calculate_shipping( $blank_package );
		}

		$packages                   = WC()->shipping()->get_packages();
		$shipping_methods           = $this->get_shipping_methods( $packages );
		$output['shipping_methods'] = $this->html_output( $shipping_methods );

		wp_send_json_success( $output );
	}

	/**
	 * Get shipping packages
	 *
	 * @return array $package
	 */
	public function get_shipping_packages() {
		$package = array(
			array(
				'contents'        => array(),
				'contents_cost'   => 0,
				'applied_coupons' => '',
				'user'            => array(
					'ID' => get_current_user_id(),
				),
				'destination'     => array(
					'country'  => $this->wc_customer()->get_shipping_country(),
					'state'    => $this->wc_customer()->get_shipping_state(),
					'postcode' => $this->wc_customer()->get_shipping_postcode(),
					'city'     => $this->wc_customer()->get_shipping_city(),
				),
				'cart_subtotal'   => 0,
			),
		);

		return $package;
	}

	/**
	 * Get the woocommerce customer
	 *
	 * @return object
	 */
	public function wc_customer() {
		return WC()->customer;
	}

	/**
	 * Get shipping methods
	 *
	 * @param array $packages Shipping Packages.
	 */
	public function get_shipping_methods( $packages ) {
		$shipping_methods = array();
		$product_id       = filter_input( INPUT_POST, 'product_id' );
		$variation_id     = filter_input( INPUT_POST, 'variation_id' );
		foreach ( $packages as $package ) {
			if ( empty( $package['rates'] ) || ! is_array( $package['rates'] ) ) {
				break;
			}

			foreach ( $package['rates'] as $id => $rate ) {
				$title                   = wc_cart_totals_shipping_method_label( $rate );
				$shipping_methods[ $id ] = apply_filters( 'wppsc_shipping_method_name', $title, $rate, $product_id, $variation_id );
			}
		}
		return $shipping_methods;
	}

	/**
	 * The HTML output of the shipping methods cost
	 *
	 * @param array $shipping_methods Shipping Methods.
	 */
	public function html_output( $shipping_methods ) {
		if ( is_array( $shipping_methods ) && ! empty( $shipping_methods ) ) {
			$html = '';
			$html = '<p>Available shipping methods</p>';
			foreach ( $shipping_methods as $id => $method ) {
				$html .= sprintf( '<li id="%s">%s</li>', esc_attr( $id ), $method );
			}
			if ( ! empty( $html ) ) {
				$shipping_methods_msg = '<ul class="wppsc-methods"> ' . $html . '</ul>';
			} else {
				$shipping_methods_msg = '';
			}
		} else {
			$shipping_methods_msg = '';
		}

		$msg = is_array( $shipping_methods ) && ! empty( $shipping_methods ) ? $shipping_methods_msg : $this->no_methods_available();

		return sprintf( '<div class="wppsc-shipping-information">%s</div>', $msg );
	}

	/**
	 * No methods available information
	 */
	public function no_methods_available() {
		return __( 'No shipping methods available for your location', 'wppsc' );
	}

	/**
	 * Shipping calculator position on the product page
	 */
	public function shipping_calculator_position() {
		$shipping_calculator_settigns = get_option( 'woocommerce_wppsc-shipping-calculator_settings' );
		if ( isset( $shipping_calculator_settigns['shipping_calculator_position'] ) ) {
			$position = apply_filters( 'wppsc_calculator_position', $shipping_calculator_settigns['shipping_calculator_position'] );
		} else {
			$position = 'woocommerce_after_add_to_cart_form';
		}

		return $position;
	}

	/**
	 * Check the shipping calculator visibility
	 *
	 * Whether the calculator should display on the product page or not
	 *
	 * @return void
	 */
	public function shipping_calculator_visibility() {
		$this->calculator_visibility = get_option( 'wppsc_enable_shipping_calc_on_product_page', 'no' );
	}
}
