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
 * Class Subway\Page_Redirect handles the 'front-end' redirection of Subway.
 *
 * @since  2.0
 */
final class Page_Redirect {

	/**
	 * Redirects pages into our login page.
	 *
	 * @return void.
	 */
	public static function index() {

		// Only execute for non logged in users.
		if ( is_user_logged_in() ) {
			return;
		}

		$queried_id = get_queried_object_id();

		$current_post = get_post( absint( $queried_id ) );

		$login_page_id = absint( get_option( 'subway_login_page' ) );

		$excluded_page = Options::get_public_post_ids();

		// Already escaped inside 'subway_get_redirect_page_url'.
		$redirect_page = Options::get_redirect_page_url(); // WPCS XSS OK.

		// Exit if site is public.
		if ( Options::is_public_site() ) {
			return;
		}

		// Check if redirect page is empty or not.
		if ( empty( $redirect_page ) ) {
			return;
		}

		// Check if buddypress activate page.
		if ( function_exists( 'bp_is_activation_page' ) ) {
			if ( bp_is_activation_page() ) {
				return;
			}
		}

		// Check if buddypress registration page.
		if ( function_exists( 'bp_is_register_page' ) ) {
			if ( bp_is_register_page() ) {
				return;
			}
		}

		// Assign 0 value to empty $post->ID to prevent exception.
		// This applies to custom WordPress pages such as BP Members Page and Groups.
		if ( empty( $current_post ) ) {
			$current_post = new \stdclass;
			$current_post->ID = 0;
		}

		$current_page_id = absint( $current_post->ID );

		// Check if $current_page_id && $selected_blog_id is equal to each other.
		// If that is the case, get the page ID instead of global $post->ID that the query returns.
		// The ID of the first post object inside the loop is not correct.
		$blog_id = absint( get_option( 'page_for_posts' ) );

		if ( is_home() ) {
			if ( $blog_id === $login_page_id ) {
				$current_page_id = $blog_id;
			}
		}

		// Only execute the script for non-loggedin visitors.
		if ( ! is_user_logged_in() ) {

			if ( $current_page_id !== $login_page_id ) {

				if ( ! in_array( $current_page_id, $excluded_page, true ) ) {

					wp_safe_redirect(
						add_query_arg(
							array( '_redirected' => 'yes' ),
							$redirect_page
						)
					);

					die();

				}
			}
		}
	}

}

