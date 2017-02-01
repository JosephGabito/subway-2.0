<?php
namespace Subway;

final class Page_Redirect
{

	public static function index() {
        
        global $post;

        if ( '' == session_id() || ! isset( $_SESSION ) ) {

            session_start();

        }

		$post_copy = &$post;

		$login_page_id = intval( get_option( 'subway_login_page' ) );

		$excluded_page = Options::get_public_post_ids();

		// Already escaped inside 'subway_get_redirect_page_url'.
		$redirect_page = Options::get_redirect_page_url();

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
		if ( empty( $post_copy ) ) {
			$post_copy = new \stdclass;
			$post_copy->ID = 0;
		}

		$current_page_id = intval( $post_copy->ID );

		// Check if $current_page_id && $selected_blog_id is equal to each other.
		// If that is the case, get the page ID instead of global $post->ID that the query returns.
		// The ID of the first post object inside the loop is not correct.
		$blog_id = intval( get_option( 'page_for_posts' ) );

		if ( is_home() ) {
			if ( $blog_id === $login_page_id ) {
				$current_page_id = $blog_id;
			}
		}

		if ( isset( $_SESSION['redirected'] ) ) {

			unset( $_SESSION['redirected'] );

			return;

		}

		// Only execute the script for non-loggedin visitors.
		if ( ! is_user_logged_in() ) {

			if ( $current_page_id !== $login_page_id ) {

				if ( ! in_array( $current_page_id, $excluded_page, true ) ) {

					$_SESSION['redirected'] = true;

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
?>
