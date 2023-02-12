<?php
/**
 * Contain all the utility methods used by the plugins
 *
 * @package   Barn2\woocommerce-product-page-shipping-calculator
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */

namespace Barn2\Plugin\WooCommerce_Product_Page_Shipping_Calculator;

class Util {

	/**
	 * Get the URL of the settings page with the appropriate query arguments
	 *
	 * @return string
	 */
	public static function get_settings_page_url() {
		return 'https://barn2.com/kb-categories/wc-shipping-calculator/';
	}
}
