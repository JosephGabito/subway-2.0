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

add_action( 'admin_menu', 'subway_admin_menu' );

add_action( 'admin_init', 'subway_register_settings' );

/**
 * Display 'Subway' link under 'Settings'
 *
 * @return void
 */
function subway_admin_menu() {

	add_options_page( 'Subway Settings', 'Subway', 'manage_options', 'subway', 'subway_options_page' );

	return;
}

/**
 * Registers all settings related to Subway.
 *
 * @return void
 */
function subway_register_settings() {

	// Register our settings section.
	add_settings_section( 'subway-page-visibility-section', __( 'Pages Visibility', 'subway' ), 'subway_section_cb', 'subway-settings-section' );

	// Register Redirect Options pages.
	add_settings_section( 'subway-redirect-section', __( 'Redirect Options', 'subway' ), 'subway_redirect_cb', 'subway-settings-section' );

	// Register the fields.
	$fields = array(
			array(
					'id' => 'subway_is_public',
					'label' => __( 'Public Website', 'subway' ),
					'callback' => 'subway_is_public_form',
					'section' => 'subway-settings-section',
					'group' => 'subway-page-visibility-section',
				),

			array(
					'id' => 'subway_login_page',
					'label' => __( 'Private Login Page', 'subway' ),
					'callback' => 'subway_login_page_form',
					'section' => 'subway-settings-section',
					'group' => 'subway-page-visibility-section',
				),
			array(
					'id' => 'subway_public_post',
					'label' => __( 'Public Posts IDs', 'subway' ),
					'callback' => 'subway_public_post',
					'section' => 'subway-settings-section',
					'group' => 'subway-page-visibility-section',
				),

			array(
					'id' => 'subway_redirect_type',
					'label' => __( 'Redirect Type', 'subway' ),
					'callback' => 'subway_redirect_option_form',
					'section' => 'subway-settings-section',
					'group' => 'subway-redirect-section',
				),
			array(
					'id' => 'subway_redirect_wp_admin',
					'label' => __( 'Bypassing <em>wp-login.php</em>', 'subway' ),
					'callback' => 'subway_lock_wp_admin',
					'section' => 'subway-settings-section',
					'group' => 'subway-redirect-section',
				),
		);

	foreach ( $fields as $field ) {

		add_settings_field( $field['id'], $field['label'], $field['callback'], $field['section'], $field['group'] );
		register_setting( 'subway-settings-group', $field['id'] );
		require_once trailingslashit( SUBWAY_DIR_PATH ) . 'settings-fields/field-' . sanitize_title( str_replace( '_','-', $field['callback'] ) ) . '.php';
	}

	// Register Redirect Page ID Settings.
	register_setting( 'subway-settings-group', 'subway_redirect_page_id' );

	// Register Redirect Custom URL Settings.
	register_setting( 'subway-settings-group', 'subway_redirect_custom_url' );

	return;
}

/**
 * Callback function for the first Section.
 *
 * @return void
 */
function subway_section_cb() {
	return;
}

/**
 * Callback function for the second Section.
 *
 * @return void
 */
function subway_redirect_cb() {
	return;
}

/**
 * Renders the 'wrapper' for our options pages.
 *
 * @return void
 */
function subway_options_page() {
	?>

	<div class="wrap">
		<h2>
			<?php esc_html_e( 'Subway Settings', 'subway' ); ?>
		</h2>
		<form id="subway-settings-form" action="options.php" method="POST">
			<?php settings_fields( 'subway-settings-group' ); ?>
			<?php do_settings_sections( 'subway-settings-section' ); ?>
			<?php submit_button(); ?>
		</form>
	</div>
	
	<?php
}
