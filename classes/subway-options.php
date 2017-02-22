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

final class Options {

	public static function get_redirect_page_url() {

		$selected_login_post_id = intval( get_option( 'subway_login_page' ) );

		if ( 0 === $selected_login_post_id ) {

			return;

		}

		$login_post = get_post( $selected_login_post_id );

		if ( ! empty( $login_post ) ) {

			return get_permalink( $login_post->ID );

		}

		return false;

	}

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
}
