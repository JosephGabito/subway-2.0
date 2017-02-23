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
 * Class Subway\Auth_Redirect handles our redirection after logging in.
 *
 * @since  1.0
 */
final class Auth_Redirect {

	/**
	 * Handles our ajax authentication.
	 *
	 * @return void
	 */
	public static function handle_authentication() {

		// Set the header type to json.
		header( 'Content-Type: application/json' );

		$log = filter_input( INPUT_POST, 'log', FILTER_SANITIZE_STRING );

		$pwd = filter_input( INPUT_POST, 'pwd', FILTER_SANITIZE_STRING );

		if ( empty( $log ) && empty( $pwd ) ) {

			$response['type'] = 'error';

			$response['message'] = esc_html__( 'Username and Password cannot be empty.', 'subway' );

		} else {

			$is_signin = wp_signon();

			$response = array();

			if ( is_wp_error( $is_signin ) ) {

				$response['type'] = 'error';

				$response['message'] = $is_signin->get_error_message();

			} else {

				$response['type'] = 'success';

				$response['message'] = esc_html__( 'You have successfully logged-in. Redirecting you in few seconds...' );

			}
		}

		$subway_redirect_url = Auth_Redirect::get_login_redirect_url( $redirect_to = '', $request = '', $user = 1 );

		$response['redirect_url'] = apply_filters( 'subway_login_redirect', $subway_redirect_url );

		echo wp_json_encode( $response );

		wp_die();

	}

	/**
	 * Returns the filtered redirect url for the current user.
	 *
	 * @param  string  $redirect_to The default redirect callback argument.
	 * @param  string  $request     The default redirect request callback argument.
	 * @param  integer $user        The current user logging in.
	 * @return string               The final redirect url.
	 */
	public static function get_login_redirect_url( $redirect_to, $request, $user ) {

		$subway_redirect_type = get_option( 'subway_redirect_type' );

		// Redirect the user to default behaviour if there are no redirect type option saved.
		if ( empty( $subway_redirect_type ) ) {

			return $redirect_to;

		}

		if ( 'default' === $subway_redirect_type ) {
			return $redirect_to;
		}

		if ( 'page' === $subway_redirect_type ) {

			// Get the page url of the selected page if the admin selected 'Custom Page' in the redirect type settings.
			$selected_redirect_page = intval( get_option( 'subway_redirect_page_id' ) );

			// Redirect to default WordPress behaviour if the user did not select page.
			if ( empty( $selected_redirect_page ) ) {

				return $redirect_to;
			}

			// Otherwise, get the permalink of the saved page and let the user go into that page.
			return get_permalink( $selected_redirect_page );

		} elseif ( 'custom_url' === $subway_redirect_type ) {

			// Get the custom url saved in the redirect type settings.
			$entered_custom_url = get_option( 'subway_redirect_custom_url' );

			// Redirect to default WordPress behaviour if the user did enter a custom url.
			if ( empty( $entered_custom_url ) ) {

				return $redirect_to;

			}

			// Otherwise, get the custom url saved and let the user go into that page.
			$current_user = wp_get_current_user();

			if ( ! empty( $user->ID ) ) {
				$entered_custom_url = str_replace( '%user_id%', $user->ID, $entered_custom_url );
			}

			if ( ! empty( $user->user_login ) ) {
				$entered_custom_url = str_replace( '%user_name%', $user->user_login, $entered_custom_url );
			}

			return $entered_custom_url;

		}

		// Otherwise, quit and redirect the user back to default WordPress behaviour.
		return $redirect_to;
	}

	/**
	 * Callback function for the 'login_url' filter defined in Subway.php
	 *
	 * @param  string $login_url    The login url.
	 * @return string               The final login url.
	 */
	public static function login_url( $login_url  ) {

		$subway_login_page = Options::get_redirect_page_url();

		// Return the default login url if there is no log-in page defined.
		if ( empty( $subway_login_page ) ) {
			return $login_url;
		}

		// Otherwise, return the Subway login page.
	    return $subway_login_page;

	}

	/**
	 * The callback function for our logout filter.
	 *
	 * @return void
	 */
	public static function logout_url() {

		$subway_login_page = Options::get_redirect_page_url();

		wp_safe_redirect( esc_url( $subway_login_page . '?loggedout=true' ) );

		exit;

		return;

	}

}
