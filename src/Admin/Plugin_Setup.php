<?php
/**
 * Plugin setup for the current plugin instance
 *
 * @package   Barn2\woocommerce-product-page-shipping-calculator
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */

namespace Barn2\Plugin\WooCommerce_Product_Page_Shipping_Calculator\Admin;

use Barn2\Plugin\WooCommerce_Product_Page_Shipping_Calculator\Plugin,
    Barn2\WPPSC_Lib\Registerable;

/**
 * {@inheritdoc}
 */
class Plugin_Setup implements Registerable {
	/**
	 * Plugin's entry file
	 *
	 * @var string
	 */
	private $file;

	/**
	 * The main plugin instance
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Get things started
	 *
	 * @param string $file
	 * @param Plugin $plugin The main plugin instance
	 */
	public function __construct( $file, Plugin $plugin ) {
		$this->file    = $file;
		$this->plugin  = $plugin;
	}

	/**
	 * Register the service
	 *
	 * @return void
	 */
	public function register() {
		register_activation_hook( $this->file, [ $this, 'on_activate' ] );
		register_deactivation_hook( $this->file, [ $this, 'on_deactivate' ] );
	}

	/**
	 * On plugin activation
     * 
     * Useful when need to create tables and transients
	 *
	 * @return void
	 */
	public function on_activate() {}

	/**
	 * Do nothing.
	 *
	 * @return void
	 */
	public function on_deactivate() {}

	/** 
	 * Useful when need to remove plugin related data from the database
	 * 
	 * @return void 
	 */
	public function on_uninstall() {}
}
