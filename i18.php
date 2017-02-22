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

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Plugin i18n.
 */
add_action( 'plugins_loaded', 'subway_localize_plugin' );

/**
 * Subway l10n callback.
 *
 * @return void
 */
function subway_localize_plugin() {

	$rel_path = SUBWAY_DIR_PATH . 'languages';

	load_plugin_textdomain( 'subway', false, $rel_path );

	return;
}