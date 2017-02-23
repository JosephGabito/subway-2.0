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
 * Subway\Enqueue class handles all the stylesheet and javascript documents for Subway
 *
 * @since  2.0
 */
final class Enqueue {

	/**
	 * Registers our CSS and Javascript to WordPress Enqueue Handler.
	 *
	 * @return void
	 */
	public static function register_js() {

		$post_id = absint( get_queried_object_id() );

		$signin_page = absint( get_option( 'subway_login_page' ) );

		// Only load the stylesheet and javascript documents inside our sign-in page.
		if ( $post_id === $signin_page ) {

			wp_enqueue_style( 'subway-style', SUBWAY_DIR_URL . 'assets/css/subway.css' );

			if ( ! is_user_logged_in() ) {

	   			wp_enqueue_script( 'subway-script', SUBWAY_DIR_URL . 'assets/js/subway.js', array( 'jquery' ) );

	   			wp_localize_script( 'subway-script', 'subway_config', array(
	   					'ajax_url' => admin_url( 'admin-ajax.php' ),
	   					'login_http_error' => esc_html__( 'An error occured while transmitting the data. Refresh the page and try again', 'subway' ),
	   				)
	   			);

			}
		}

		return;

	}

}
