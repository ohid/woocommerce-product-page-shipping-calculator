<?php
namespace Barn2\Plugin\WooCommerce_Product_Page_Shipping_Calculator;

use Barn2\WPPSC_Lib\Plugin\Simple_Plugin,
	Barn2\WPPSC_Lib\Registerable,
	Barn2\WPPSC_Lib\Translatable,
	Barn2\WPPSC_Lib\Util as Lib_Util;

/**
 * The main plugin class for WooCommerce Product Page Shipping Calculator.
 *
 * @package   Barn2\woocommerce-product-page-shipping-calculator
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */
class Plugin extends Simple_Plugin implements Registerable, Translatable {

	const NAME    = 'WooCommerce Product Page Shipping Calculator';
	const ITEM_ID = 509974;

	private $services;

	/**
	 * Constructor
	 *
	 * @param  string $file The path of the main plugin file
	 * @param  string $version The current version of the plugin
	 * @return void
	 */
	public function __construct( $file = null, $version = null ) {
		parent::__construct(
			[
				'name'               => self::NAME,
				'item_id'            => self::ITEM_ID,
				'version'            => $version,
				'file'               => $file,
				'is_woocommerce'     => Lib_Util::is_woocommerce_active(),
				'settings_path'      => 'https://barn2.com/kb-categories/wc-shipping-calculator/',
				'documentation_path' => 'kb-categories/wc-shipping-calculator/?utm_source=settings&utm_medium=settings&utm_campaign=settingsinline&utm_content=wppsc-settings',
			]
		);

		$this->services = [];

		if ( Lib_Util::is_admin() ) {
			$this->services['admin/controller'] = new Admin\Admin_Controller( $this );
		}

		$this->services = array_merge(
			$this->services,
			[
				'product_page_calculator' => new Product_Page_Calculator( $this ),
				'shipping_settings' 	  => new Shipping_Settings( $this )
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function register() {
		$plugin_setup = new Admin\Plugin_Setup( $this->get_file(), $this );
		$plugin_setup->register();
		
		add_action( 'init', [ $this, 'load_plugin' ] );
		add_action( 'init', [ $this, 'load_textdomain' ], 5 );
	}

	/**
	 * {@inheritdoc}
	 */
	public function load_plugin() {
		Lib_Util::register_services( $this->services );
	}

	/**
	 * {@inheritdoc}
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'wppsc', false, $this->get_slug() . '/languages' );
	}

	/**
	 * Return the local path of the `Admin` folder under the plugin root folder
	 *
	 * @param  string $file The subpath located in the Admin folder
	 * @return string
	 */
	public function get_admin_path( $file ) {
		return wp_normalize_path( $this->get_dir_path() . '/src/Admin/' . $file );
	}
}
