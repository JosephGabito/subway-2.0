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

namespace Subway;

final class Admin_Redirect {

	public static function index() {

		$is_redirect_admin = get_option( 'subway_redirect_wp_admin' );

		// Only run this function when on wp-login.php.
		if ( ! in_array( $GLOBALS['pagenow'], array( 'wp-login.php' ), true ) ) {
			return;
		}

		if ( ! $is_redirect_admin ) {
			return;
		}

		// Has any errors?
		$has_error = filter_input( INPUT_GET, 'error', FILTER_SANITIZE_STRING );

		// Error Types.
		$has_type = filter_input( INPUT_GET, 'type', FILTER_SANITIZE_STRING );

		// Set the default to our login page.
		$redirect_page = Options::get_redirect_page_url();

		if ( $has_error && $has_type ) {

			$redirect_to = add_query_arg( array(
				'login' => 'failed',
				'type' => $has_type,
			), $redirect_page );

			wp_safe_redirect( esc_url_raw( $redirect_to ) );

			exit;

		}

		// Bypass wp-login.php?action=* link.
		if ( filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING ) ) {
			return;
		}

		// Bypass no redirect action
		if ( filter_input( INPUT_GET, 'no_redirect', FILTER_VALIDATE_BOOLEAN ) ) {
			return;
		}

		// Do not redirect if BuddyPress activation page.
		if ( function_exists( 'bp_is_activation_page' ) ) {
			if ( bp_is_activation_page() ) {
				return;
			}
		}

		// Do not redirect if BuddyPress registration page.
		if ( function_exists( 'bp_is_register_page' ) ) {
			if ( bp_is_register_page() ) {
				return;
			}
		}

		// Holds the current URI string for checking.
		$curr_paged = basename( $_SERVER['REQUEST_URI'] );

		if ( empty( $redirect_page ) ) {
			return;
		}

		// If user visits wp-admin or wp-login.php, redirect them.
		if ( strstr( $curr_paged, 'wp-login.php' ) ) {

			// Do not redirect interim login.
			if ( isset( $_GET['interim-login'] ) ) {
				return;
			}

			// Check if there is an action present.
			// The action might represent user trying to log out.
			if ( isset( $_GET['action'] ) ) {

				$action = $_GET['action'];

				if ( 'logout' === $action ) {

					return;

				}
			}

			// Only redirect if there are no incoming post data.
			if ( empty( $_POST ) ) {

				wp_safe_redirect( $redirect_page );

				exit;
			}

			// Redirect to error page if user left username and password blank.
			if ( ! empty( $_POST ) ) {

				if ( empty( $_POST['log'] ) && empty( $_POST['pwd'] ) ) {

					$redirect_to = add_query_arg( array(
						'login' => 'failed',
						'type' => '__blank',
					), $redirect_page );

					wp_safe_redirect( esc_url_raw( $redirect_to ) );


				} elseif ( empty( $_POST['log'] ) && ! empty( $_POST['pwd'] ) && ! empty( $_POST['redirect_to'] ) ) {
					// Username empty.
					$redirect_to = add_query_arg( array(
						'login' => 'failed',
						'type' => '__userempty',
					), $redirect_page );

					wp_safe_redirect( esc_url_raw( $redirect_to ) );


				} elseif ( ! empty( $_POST['log'] ) && empty( $_POST['pwd'] ) && ! empty( $_POST['redirect_to'] ) ) {
					// Password empty.
					$redirect_to = add_query_arg( array(
						'login' => 'failed',
						'type' => '__passempty',
					), $redirect_page );

					wp_safe_redirect( esc_url_raw( $redirect_to ) );


				} else {

					// Generic.
					$redirect_to = add_query_arg( array(
						'login' => 'failed',
						'type' => 'default',
					), $redirect_page );

					wp_safe_redirect( esc_url_raw( $redirect_to ) );

				}
			}
		}

		return;
	}

	public static function handle_authentication() {

		// Set the header type to json.
		header('Content-Type: application/json');

		$log = filter_input( INPUT_POST, 'log', FILTER_SANITIZE_STRING );

		$pwd = filter_input( INPUT_POST, 'pwd', FILTER_SANITIZE_STRING );

		if ( empty( $log ) && empty( $pwd ) ) {

			$response['type'] = 'error';

			$response['message'] = esc_html__('Username and Password cannot be empty.', 'subway');;

		} else {

			$is_signin = wp_signon();

			$response = array();

			if ( is_wp_error( $is_signin ) ) {

				$response['type'] = 'error';

				$response['message'] = $is_signin->get_error_message();

			} else {

				$response['type'] = 'success';

				$response['message'] = esc_html__('You have successfully logged-in. Redirecting you in few seconds...');

			}

		}

		$subway_redirect_url = Admin_Redirect::authentication_200( $redirect_to ='', $request = '', $user =1 );
		
		$response['redirect_url'] = apply_filters('subway_login_redirect', $subway_redirect_url );

		echo json_encode( $response );

		wp_die();

	}

	public static function authentication_200( $redirect_to, $request, $user ) {

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

			if ( ! empty ( $user->ID ) ) {
				$entered_custom_url = str_replace( '%user_id%', $user->ID, $entered_custom_url );
			}

			if ( ! empty ( $user->user_login ) ) {
				$entered_custom_url = str_replace( '%user_name%', $user->user_login, $entered_custom_url );
			}
			return $entered_custom_url;

		}

		// Otherwise, quit and redirect the user back to default WordPress behaviour.
		return $redirect_to;
	}
	
}

