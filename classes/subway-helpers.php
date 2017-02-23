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
 * Helper methods for Subway
 *
 * @since  1.0
 */
final class Helpers {

	/**
	 * Exit wrapper.
	 * 
	 * @return void
	 */
	public static function close() {
		exit;
	}
}
