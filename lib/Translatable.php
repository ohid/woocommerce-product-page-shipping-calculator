<?php
/**
 * Something that can be translated by WordPress.
 *
 * @package   Barn2\barn2-lib
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 * @version   1.0
 */

namespace Barn2\WPPSC_Lib;

interface Translatable {

	/**
	 * Load the text domain for a theme or plugin.
	 *
	 * @return void
	 */
	public function load_textdomain();

}
