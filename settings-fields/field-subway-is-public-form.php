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
 * Callback function for 'subway_is_public' setting
 *
 * @return void
 */
function subway_is_public_form() {

	echo '<label for="subway_is_public"><input ' . checked( 1, get_option( 'subway_is_public' ), false ) . ' value="1" name="subway_is_public" id="subway_is_public" type="checkbox" class="code" /> Check to make all of your posts and pages visible to public.</label>';
	echo '<p class="description">' . esc_html__( 'Check to ignore all private options and make your site return to its original state (public) without uninstalling Subway. All settings here and in the individual posts or pages will be ignored. Uncheck to make your site accessible to members only', 'subway' ) . '</p>';

	return;
}
