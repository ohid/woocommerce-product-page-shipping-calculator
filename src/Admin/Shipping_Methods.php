<?php
/**
 * Class Shipping_Methods
 *
 * @package   Barn2\woocommerce-product-page-shipping-calculator
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */

namespace Barn2\Plugin\WooCommerce_Product_Page_Shipping_Calculator\Admin;

use Barn2\WPPSC_Lib\Plugin\Simple_Plugin,
	Barn2\WPPSC_Lib\Registerable,
	Barn2\WPPSC_Lib\Service;

class Shipping_Methods implements Registerable, Service {

	/**
	 * The main plugin instance
	 *
	 * @var Plugin
	 */
	private $plugin;

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
	 * Register shipping methods
	 *
	 * @return void
	 */
	public function register() {
		add_filter( 'woocommerce_shipping_methods', array( $this, 'register_shipping_methods' ) );
	}

	/**
	 * Add the shipping methods to WooCommerce
	 *
	 * @param array $methods WooCommerce Default Shipping Methods.
	 * @return $methods
	 */
	public function register_shipping_methods( $methods ) {
		$methods[] = new Shipping_Methods\Shipping_Calculator( $this->plugin );
		return $methods;
	}
}
