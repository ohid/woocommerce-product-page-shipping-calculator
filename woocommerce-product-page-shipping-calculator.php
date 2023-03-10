<?php
/**
 * The main plugin file for WooCommerce Product Page Shipping Calculator.
 *
 * @package   Barn2\woocommerce-product-page-shipping-calculator
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 *
 * @wordpress-plugin
 * Plugin Name:     WooCommerce Product Page Shipping Calculator
 * Plugin URI:      https://barn2.co.uk/wordpress-plugins/woocommerce-product-page-shipping-calculator
 * Description:     Display a shipping calculator on the product page, allowing customers to calculate shipping costs before adding products to their cart.
 * Version:         1.0
 * Author:          Barn2 Plugins
 * Author URI:      https://barn2.com/wordpress-plugins/woocommerce-product-options/
 * Text Domain:     wppsc
 * Domain Path:     /languages
 *
 * Copyright:       Barn2 Media Ltd
 * License:         GNU General Public License v3.0
 * License URI:     https://www.gnu.org/licenses/gpl.html
 */

namespace Barn2\Plugin\WooCommerce_Product_Page_Shipping_Calculator;

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'is_plugin_active ' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}
if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	/**
	 * Display notice when woocomomerce is inactive
	 */
	function wppsc_shipping_calculator_notice() {
		?>
		<div class="error notice">
			<p><?php esc_html_e( 'Please Install and Activate WooCommerce plugin first, the shipping calculator plugin requires WooCommerce to be installed and activated.', 'wppsc' ); ?></p>
		</div>
		<?php
	}
	add_action( 'admin_notices', 'Barn2\Plugin\WooCommerce_Product_Page_Shipping_Calculator\wppsc_shipping_calculator_notice' );
	return;
}

const PLUGIN_VERSION = '1.0';
const PLUGIN_FILE    = __FILE__;

// Autoloader.
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Helper function to access the shared plugin instance.
 *
 * @return Plugin
 */
function wppsc() {
	return Plugin_Factory::create( PLUGIN_FILE, PLUGIN_VERSION );
}

// Load the plugin.
wppsc()->register();
