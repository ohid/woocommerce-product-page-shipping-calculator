<?php
/**
 * General admin functions for WooCommerce Product Page Shipping Calculator.
 *
 * @package   Barn2\woocommerce-product-page-shipping-calculator
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */

namespace Barn2\Plugin\WooCommerce_Product_Page_Shipping_Calculator\Admin;

use Barn2\WPPSC_Lib\Plugin\Simple_Plugin,
	Barn2\WPPSC_Lib\Registerable,
	Barn2\WPPSC_Lib\Service,
	Barn2\WPPSC_Lib\Service_Container,
	Barn2\Plugin\WooCommerce_Product_Page_Shipping_Calculator\Util;

class Admin_Controller implements Registerable, Service {

	use Service_Container;

	/**
	 * The main plugin instance
	 *
	 * @var Simple_Plugin
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
	 * {@inheritdoc}
	 */
	public function get_services() {
		$services = array(
			'shipping_methods' => new Shipping_Methods( $this->plugin ),
		);
		return $services;
	}

	/**
	 * {@inheritdoc}
	 */
	public function register() {
		$this->register_services();

		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_filter( 'plugin_action_links_' . $this->plugin->get_basename(), array( $this, 'add_settings_link' ) );
	}

	/**
	 * Enqueue the stylesheet required by this plugin
	 *
	 * @param string $hook The value of the page query argument.
	 * @return void
	 */
	public function load_scripts( $hook ) {
		$screen = get_current_screen();
		if ( $screen->id !== 'woocommerce_page_wc-settings' ) {
			return;
		}

		// load necessary scripts specifically on the wc settings page.
		wp_enqueue_style( 'wppsc-shipping-settings', plugin_dir_url( $this->plugin->get_file() ) . 'assets/css/admin-styles.css', array(), $this->plugin->get_version() );
	}

	/**
	 * Add a link to the main page of this plugin to the list of links in the
	 * name column of the plugins table list
	 *
	 * @param  array $links The list of links being filtered.
	 * @return array
	 */
	public function add_settings_link( $links ) {
		$settings_url = Util::get_settings_page_url();

		array_unshift( $links, sprintf( '<a href="%1$s">%2$s</a>', esc_url( $settings_url ), __( 'Settings', 'easy-post-types-fields' ) ) );

		return $links;
	}
}
