<?php
/**
 * Class Shipping_Settings
 * 
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

class Shipping_Settings implements Registerable, Service {

	/**
	 * The main plugin instance
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Constructor
	 *
	 * @param  Simple_Plugin $plugin The main instance of this plugin
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
		// Shipping options
		add_filter('woocommerce_shipping_settings', array( $this, 'add_shipping_options' ) );
	}

	/**
	 * Add a new option to the woocommerce shipping settings
	 * 
	 * @return $settings 
	 */
	public function add_shipping_options( $settings ) {
		$new_setting = array(
			array(
				"desc" => "Enable the shipping calculator on the product page",
				"id" => "wppsc_enable_shipping_calc_on_product_page",
				"default" => "no",
				"type" => "checkbox",
				"checkboxgroup" => "middle"
			)
		);
		array_splice( $settings, 2, 0, $new_setting );
		return $settings;
	}

}