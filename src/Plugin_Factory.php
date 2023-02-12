<?php
/**
 * Factory to create/return the shared plugin instance.
 *
 * @package   Barn2\woocommerce-product-page-shipping-calculator
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */

namespace Barn2\Plugin\WooCommerce_Product_Page_Shipping_Calculator;

class Plugin_Factory {

	/**
	 * The main plugin instance
	 *
	 * @var Plugin
	 */
	private static $plugin = null;

	/**
	 * Create/return the shared plugin instance.
	 *
	 * @param string $file The plugin file.
	 * @param string $version The plugin version.
	 * @return Barn2\Plugin\WooCommerce_Product_Page_Shipping_Calculator\Plugin
	 */
	public static function create( $file, $version ) {
		if ( null === self::$plugin ) {
			self::$plugin = new Plugin( $file, $version );
		}
		return self::$plugin;
	}
}
