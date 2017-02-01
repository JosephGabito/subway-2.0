<?php
final class Subway_Admin_Redirect {

	public static function index() {
		
		// Only run this function when on wp-login.php.
		if ( ! in_array( $GLOBALS['pagenow'], array( 'wp-login.php' ), true ) ) {
			return;
		}

		// Bypass login if specified.
		$no_redirect = filter_input( INPUT_GET, 'no_redirect', FILTER_VALIDATE_BOOLEAN );

		// Bypass wp-login.php?action=*.
		$has_action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );

		// Has any errors?
		$has_error = filter_input( INPUT_GET, 'error', FILTER_SANITIZE_STRING );

		// Error Types.
		$has_type = filter_input( INPUT_GET, 'type', FILTER_SANITIZE_STRING );

		// Set the default to our login page.
		$redirect_page = subway_get_redirect_page_url();

		if ( $has_error && $has_type ) {

			$redirect_to = add_query_arg( array(
				'login' => 'failed',
				'type' => $has_type,
			), $redirect_page );

				wp_safe_redirect( esc_url_raw( $redirect_to ) );

				die();
		}

		// Bypass wp-login.php?action=* link.
		if ( $has_action ) {
			return;
		}

		if ( $no_redirect ) {
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

		// Store for checking if this page equals wp-login.php.
		$curr_paged = basename( $_SERVER['REQUEST_URI'] );

		if ( empty( $redirect_page ) ) {

			return;

		}

		// Ff user visits wp-admin or wp-login.php, redirect them.
		if ( strstr( $curr_paged, 'wp-login.php' ) ) {

			if ( isset( $_GET['interim-login'] ) ) {
				return;
			}

			// Check if there is an action present action might represent user trying to log out.
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
}

