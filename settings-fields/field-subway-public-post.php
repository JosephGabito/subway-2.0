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

function subway_public_post() {

	echo '<textarea id="subway_public_post" name="subway_public_post_deprecated" rows="5" cols="95" readonly="readonly">' . esc_attr( trim( get_option( 'subway_public_post' ) ) ) . '</textarea>';

	echo '<p class="description">' . nl2br( esc_html( "Enter the IDs of posts and pages that you wanted to show in public. You need to separate it by ',' (comma),  for example: 143,123,213. Alternatively, you can enable public viewing of all of your pages and posts by checking the 'Public Website' option above.", 'subway' ) );
	echo '<span class="subway-settings-notice">' . nl2br( esc_html( ' This setting is now disabled on Subway 2.0.9 and future versions. To make your post/page private, go to your individual post/page and check the "Subway: Visibility Option" checkbox.', 'subway' ) ) . '</span>' . '</p>';

	return;

}
