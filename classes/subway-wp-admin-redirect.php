<?php
namespace Subway;

final class Admin_Redirect {

	public static function index() {

		// Only run this function when on wp-login.php.
		if ( ! in_array( $GLOBALS['pagenow'], array( 'wp-login.php' ), true ) ) {
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

			die();

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

		// Holds the curret URI string for checking.
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

	public static function authentication_fail() {

		// Pull the sign-in page url.
		$sign_in_page = wp_login_url();

		$custom_sign_in_page_url = Options::get_redirect_page_url();

		if ( ! empty( $custom_sign_in_page_url ) ) {

			$sign_in_page = $custom_sign_in_page_url;

		}

		// Check that were not on the default login page.
		if ( ! empty( $sign_in_page ) && ! strstr( $sign_in_page,'wp-login' ) && ! strstr( $sign_in_page,'wp-admin' ) && null !== $user ) {

			// make sure we don't already have a failed login attempt.
			if ( ! strstr( $sign_in_page, '?login=failed' ) ) {

				// Redirect to the login page and append a querystring of login failed.
				$permalink = add_query_arg( array(
					'login' => 'failed',
					'type' => 'default',
				), $custom_sign_in_page_url );

				wp_safe_redirect( esc_url_raw( $permalink ) );

				die();

			} else {

				wp_safe_redirect( $sign_in_page );

				die();
			}

			return;
		}

		return;
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

			$entered_custom_url = str_replace( '%user_id%', $user->ID, $entered_custom_url );

			$entered_custom_url = str_replace( '%user_name%', $user->user_login, $entered_custom_url );

			return $entered_custom_url;

		}

		// Otherwise, quit and redirect the user back to default WordPress behaviour.
		return $redirect_to;
	}
	
}

