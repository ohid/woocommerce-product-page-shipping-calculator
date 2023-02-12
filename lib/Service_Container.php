<?php
/**
 * A trait for a service container.
 *
 * @package   Barn2\barn2-lib
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 * @version   1.2
 */

namespace Barn2\WPPSC_Lib;

trait Service_Container {

	/**
	 * List of services in an array
	 *
	 * @var $services
	 */
	private $services = array();

	/**
	 * Register the services
	 *
	 * @return void
	 */
	public function register_services() {
		Util::register_services( $this->_get_services() );
	}

	/**
	 * Get services list
	 *
	 * @return $services
	 */
	private function _get_services() { //phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		if ( empty( $this->services ) ) {
			$this->services = $this->get_services();
		}

		return $this->services;
	}

	/**
	 * Get services
	 *
	 * @return array
	 */
	public function get_services() {
		// Overridden by classes using this trait.
		return array();
	}

	/**
	 * Get service by id
	 *
	 * @param string $id Service id.
	 */
	public function get_service( $id ) {
		$services = $this->_get_services();

		return isset( $services[ $id ] ) ? $services[ $id ] : null;
	}

}
