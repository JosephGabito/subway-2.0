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
 * Class Subway\Options handles basic 'get' of options saved in our settings page.
 *
 * @since  2.0
 */
final class Options {

	/**
	 * Get the redirect page url.
	 *
	 * @return mixed The redirect url of our settings. Otherwise, false.
	 */
	public static function get_redirect_page_url() {

		$selected_login_post_id = intval( get_option( 'subway_login_page' ) );

		if ( 0 === $selected_login_post_id ) {

			return;

		}

		$login_post = get_post( $selected_login_post_id );

		if ( ! empty( $login_post ) ) {

			return trailingslashit( get_permalink( $login_post->ID ) );

		}

		return false;

	}

	/**
	 * Fetches the public post ids.
	 *
	 * @return array The collection of public 'post' IDs.
	 */
	public static function get_public_post_ids() {

		$subway_public_post = get_option( 'subway_public_post' );

		$excluded_pages_collection = array();

		if ( ! empty( $subway_public_post ) ) {

			$excluded_pages_collection = explode( ',', $subway_public_post );

		}

		// Should filter it by integer, spaces will be ignored, other strings.
		// Will be converted to zero '0'.
		return array_filter( array_map( 'intval', $excluded_pages_collection ) );

	}

	/**
	 * Check if site is public or not.
	 *
	 * @return boolean True on success. Otherwise, false.
	 */
	public static function is_public_site() {

		$subway_public_post = get_option( 'subway_is_public' );

		if ( ! empty ( $subway_public_post ) ) {

			return true;

		}
		return false;
	}
}
