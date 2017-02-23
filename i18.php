<?php
/**
 * This file is part of the Subway WordPress Plugin Package.
 *
 * (c) Joseph Gabito <joseph@useissuestabinstead.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package Subway
 */

namespace Subway;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Plugin i18n.
 */
final class i18 {

	/**
	 * Class Constructor.
	 *
	 * @return  void
	 */
	public function __construct() {

		add_action( 'plugins_loaded', array( $this, 'subway_localize_plugin' ) );

		return;
	}

	/**
	 * Subway l8n callback.
	 *
	 * @return void
	 */
	function subway_localize_plugin() {

		$rel_path = SUBWAY_DIR_PATH . 'languages';

		load_plugin_textdomain( 'subway', false, $rel_path );

		return;
	}

}

$subwayi18 = new i18();
