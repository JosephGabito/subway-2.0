<?php
/**
 * This file is part of the Subway WordPress Plugin Package.
 *
 * (c) Joseph Gabito <joseph@useissuestabinstead.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * PHP Version 5.4
 * 
 * @category Subway\Templates
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/subway
 * @link     github.com/codehaiku/subway The Plugin Repository
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

$error_login_message = '';

$message_types = array();

$http_request_login = filter_input( INPUT_GET, 'login', FILTER_SANITIZE_SPECIAL_CHARS );

$http_request_type = filter_input( INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS );

$http_request_logout = filter_input( INPUT_GET, 'loggedout', FILTER_SANITIZE_SPECIAL_CHARS );

if ( isset( $http_request_login ) ) {

	if ( 'failed' === $http_request_login ) {

		if ( isset( $http_request_type ) ) {

			$message_types = array(

				'default' => array(
						'message' => __( 'There was an error trying to sign-in to your account. Make sure the credentials below are correct.', 'subway' ),
					),
				'__blank' => array(
						'message' => __( 'Required: Username and Password cannot be empty.', 'subway' ),
					),
				'__userempty' => array(
						'message' => __( 'Required: Username cannot be empty.', 'subway' ),
					),
				'__passempty' => array(
						'message' => __( 'Required: Password cannot be empty.', 'subway' ),
					),
				'fb_invalid_email' => array(
						'message' => __( 'Facebook email address is invalid or is not yet verified.', 'subway' ),
					),
				'fb_error' => array(
						'message' => __( 'Facebook Application Error. Misconfigured or App is rejected.', 'subway' ),
					),
				'app_not_live' => array(
						'message' => __( 'Unable to fetch your Facebook Profile.', 'subway' ),
					),
				'gears_username_or_email_exists' => array(
						'message' => __( 'Username or email address already exists', 'subway' ),
					),
				'gp_error_authentication' => array(
						'message' => __( 'Google Plus Authentication Error. Invalid Client ID or Secret.', 'subway' ),
					),
			);

			$message = $message_types['default']['message'];

			if ( array_key_exists( $http_request_type, $message_types ) ) {

				$message = $message_types[ $http_request_type ]['message'];

			}

			$error_login_message = '<div id="message" class="error">' . esc_html( $message ) . '</div>';

		} else {

			$error_login_message = '<div id="message" class="error">' . esc_html__( 'Error: Invalid username and password combination.', 'subway' ) . '</div>';

		}
	}
}

if ( isset( $http_request_logout ) ) {
	$error_login_message = '<div id="message" class="success">' . esc_html__( 'You have logged out successfully.', 'subway' ) . '</div>';
}

$http_request_redirected = filter_input( INPUT_GET, '_redirected', FILTER_SANITIZE_SPECIAL_CHARS );

if ( isset( $http_request_redirected ) ) {
	$error_login_message = '<div id="message" class="success">' . esc_html__( 'Members only page. Please use the login form below to access the page.', 'subway' ) . '</div>';
}

?>
<?php if ( ! is_user_logged_in() ) { ?>
	<div class="mg-top-35 mg-bottom-35 subway-login-form">
		<div class="subway-login-form-form">
			<div class="subway-login-form__actions">
				<h3>
					<?php esc_html_e( 'Account Sign-in', 'subway' ); ?>
				</h3>
				<?php do_action( 'gears_login_form' ); ?>
			</div>
			<div class="subway-login-form-message">
				<?php echo wp_kses_post( $error_login_message ); ?>
			</div>
			<div class="subway-login-form__form">
				<?php echo wp_login_form( $atts ); ?>
			</div>
		</div>
	</div>
<?php } else { ?>
	<div class="mg-top-35 mg-bottom-35 subway-login-sucessfull">
		<p style="margin-bottom: 0px;">
			<?php $success_message = apply_filters( 'subway_login_message_success', esc_html__( 'You are currently logged-in.', 'subway' ) ); ?>
			<?php echo esc_html( $success_message ); ?>
			
		</p>
	</div>
<?php } ?>
