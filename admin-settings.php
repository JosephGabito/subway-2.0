<?php
add_action( 'admin_init', 'subway_admin_settings_index' );

/**
 * Register all the settings inside 'Reading' section
 * of WordPress Administration Panel
 *
 * @return void
 */
function subway_admin_settings_index() {

	// Add new 'Pages Visibility Settings'.
	add_settings_section(
		'subway_setting_section',
		__( 'Pages Visibility Settings', 'subway' ),
		'subway_setting_section_callback_function',
		'reading'
	);

	// WP Options 'subway_public_post'.
	add_settings_field(
		'subway_public_post',
		__( 'Public Posts IDs', 'subway' ),
		'subway_setting_callback_function',
		'reading',
		'subway_setting_section'
	);

	// WP Options 'subway_is_public'.
	add_settings_field(
		'subway_is_public',
		__( 'Public Website', 'subway' ),
		'subway_is_public_form',
		'reading',
		'subway_setting_section'
	);

	// WP Options 'subway_login_page'.
	add_settings_field(
		'subway_login_page',
		__( 'Login Page', 'subway' ),
		'subway_login_page_form',
		'reading',
		'subway_setting_section'
	);

	// WP Options 'subway_redirect_type'.
	add_settings_field(
		'subway_redirect_type',
		__( 'Redirect Type', 'subway' ),
		'subway_redirect_option_form',
		'reading',
		'subway_setting_section'
	);

	// WP Options 'subway_bypassing_info'.
	add_settings_field(
		'subway_bypassing_info',
		__( 'Bypassing Redirect', 'subway' ),
		'subway_bypassing_option_form',
		'reading',
		'subway_setting_section'
	);

	// Register all the callback settings id.
	register_setting( 'reading', 'subway_public_post' );
	register_setting( 'reading', 'subway_is_public' );
	register_setting( 'reading', 'subway_login_page' );
	register_setting( 'reading', 'subway_redirect_type' );

	register_setting( 'reading', 'subway_redirect_page_id' );
	register_setting( 'reading', 'subway_redirect_custom_url' );

}


/**
 * Register a callback function that will handle
 * the 'Pages Visibility Settings' Page.
 *
 * @return void
 */
function subway_setting_section_callback_function() {
	// Do nothing.
	return;
}

/**
 * Callback function for 'subway_public_post' setting.
 *
 * @return void
 */
function subway_setting_callback_function() {

	echo '<textarea id="subway_public_post" name="subway_public_post" rows="5" cols="95">' . esc_attr( trim( get_option( 'subway_public_post' ) ) ) . '</textarea>';

	echo '<p class="description">' . nl2br( esc_html( "Enter the IDs of posts and pages that you wanted to show in public. You need to separate it by ',' (comma),  \nfor example: 143,123,213. Alternatively, you can enable public viewing of all of your pages and posts by checking the option below.", 'subway' ) ) . '</p>';

	return;
}

/**
 * Callback function for 'subway_is_public' setting
 *
 * @return void
 */
function subway_is_public_form() {

	echo '<label for="subway_is_public"><input ' . checked( 1, get_option( 'subway_is_public' ), false ) . ' value="1" name="subway_is_public" id="subway_is_public" type="checkbox" class="code" /> Check to make all of your posts and pages visible to public.</label>';
	echo '<p class="description">' . esc_html__( 'Pages like user profile, members, and groups are still only available to the rightful owner of the profile.', 'subway' ) . '</p>';

	return;
}

/**
 * Callback function for 'subway_login_page' setting
 *
 * @return void
 */
function subway_login_page_form() {

	$subway_login_page_id = intval( get_option( 'subway_login_page' ) );

	if ( ! empty( $subway_login_page_id ) ) {

		$login_page_object = get_post( $subway_login_page_id );

		if ( ! empty( $login_page_object )  && isset( $login_page_object->post_content ) ) {

			// Automatically prepend the login shortcode if no
			// Shortcode exists in the selected login page.
			if ( ! has_shortcode( $login_page_object->post_content, 'subway_login' ) ) {

				$new_post_object = array(
				  'ID' => $login_page_object->ID,
				  'post_content' => '[subway_login] ' . $login_page_object->post_content,// Prepend Only.
				 );

				wp_update_post( $new_post_object );
			}
		}
	}

	wp_dropdown_pages(
		array(
		'name' => 'subway_login_page',
		'selected' => intval( $subway_login_page_id ),
		'show_option_none' => esc_html__( '---', 'subway' ),
		)
	);

	echo '<p class="description">' . sprintf( esc_html__( 'Select a page to use as a login page for your website. Select a login page and save the changes to make your site private. Leave blank to make your site public. %1$s.', 'subway' ), '<span style="font-weight: bold; color: #ad4a4a;">' . esc_html__( 'You need to add "[subway_login]" shortcode in the selected page to show the login form (this is automatic)', 'subway' ) ) . '</span></p>';

	return;
}

/**
 * Callback function for 'subway_bypassing_info' setting
 *
 * @return void
 */
function subway_bypassing_option_form() {

	echo "<p class='description'>";

	echo sprintf(
		__(
			"Use the following link to bypass the log-in page 
		and go directly to your website's wp-login URL (http://yoursiteurl.com/wp-login.php): 
		<br><br> <strong>%s</strong>", 'subway'
		),
		site_url( 'wp-login.php?no_redirect=true' )
	);

	echo '</p>';

	return;
}

/**
 * Callback function for 'subway_redirect_option' setting.
 *
 * @return void
 */
function subway_redirect_option_form() {
	?>

	<style>
		
		.subway-redirect-option-section {
			background: #fff;
			padding: 15px 20px;
			margin: 15px 0;
		}

	</style>
	
	<p class="subway-redirect-type-choices">

		<!-- Page -->
		<label for="subway_use_page">
			
			<input <?php checked( 'page', get_option( 'subway_redirect_type' ), true ); ?> value="page" name="subway_redirect_type" id="subway_use_page" type="radio" class="code" /> 
			
			<?php esc_html_e( 'Custom Page', 'subway' ); ?>

		</label>

		<!-- Custom URL -->

		&nbsp;&nbsp;&nbsp;

		<label for="subway_use_custom_url">
			
			<input <?php checked( 'custom_url', get_option( 'subway_redirect_type' ), true ); ?> value="custom_url" name="subway_redirect_type" id="subway_use_custom_url" type="radio" class="code" /> 
			
			<?php esc_html_e( 'Custom URL', 'subway' ); ?>

		</label>

		<!-- Default Behavior -->

		&nbsp;&nbsp;&nbsp;

		<label for="subway_use_default">
			
			<input <?php checked( 'default', get_option( 'subway_redirect_type' ), true ); ?> value="default" name="subway_redirect_type" id="subway_use_default" type="radio" class="code" /> 
			
			<?php esc_html_e( 'Default Behavior', 'subway' ); ?>

		</label>

	</p>

	<p class="description">
		<?php
			esc_html_e('Where do you want your members to go after logging-in? You can pick a page, a Custom URL, or just a WordPress Default behavior. Page and Custom URL has its settings.', 'subway'
			);
		?>
	</p>
	
	<div id="subway_redirect_page_option_section" class="hidden subway-redirect-option-section">
		
		<label for="subway_redirect_page_id">
			<?php esc_html_e( 'Select Page' ); ?>
		</label>

		<?php
			// Choosing page for redirect.
			wp_dropdown_pages(
				array(
						'name' => 'subway_redirect_page_id',
						'selected' => intval( get_option( 'subway_redirect_page_id' ) ),
						'show_option_none' => esc_html__( '-', 'subway' ),
				)
			);

		?>

		<p class="description">
			
			<?php _e( 'The selected page will be used as the redirect endpoint for all of your users. Selecting blank (-) will redirect the user to the default redirect defined in WordPress or other plugins. Choose "Custom URL" if you want to redirect to a custom URL or domain.', 'subway' ); ?>

		</p>

	</div>

	<div id="subway_redirect_custom_url_option_section" class="hidden subway-redirect-option-section">
		
		<label for="subway_redirect_custom_url">
			<?php esc_attr_e( 'Enter Redirect URL:', 'subway' ); ?>
		</label>

		<input value="<?php echo esc_attr( esc_url( get_option( 'subway_redirect_custom_url' ) ) ); ?>" type="text" name="subway_redirect_custom_url" placeholder="<?php esc_attr_e( 'http://', 'subway' ); ?>" 
		id="subway_redirect_custom_url" size="75" />

		<p class="description"><br>

		<?php
			echo sprintf( __( 'When entering a custom domain, you can use a variable string such us: %1$s and %2$s. For example, http://yoursiteurl.com/members/<strong>%2$s</strong> will translate to http://yoursiteurl/members/<strong>admin</strong> where "admin" is equal to the %2$s variable; http://yoursiteurl.com/users/<strong>%1$s</strong> will translate to http://yoursiteurl.com/users/<strong>4</strong> where "4" is equal to the %1$s. Both variables refer to the current user that is logged-in.', 'subway' ), '%user_id%', '%user_name%' );
		?>

		</p><br>

		<p class="description">

			<?php esc_attr_e( 'Leave empty to use existing WordPress or other 3rd party plugin redirect option.', 'subway' ); ?>

		</p><br>

		<p class="description">

			<?php esc_html_e( 'Warning: External URLs are not supported by WordPress Function (wp_safe_redirect) and will be redirected back to default WordPress behavior.', 'subway' ); ?>

		</p> 

	</div>

	<!-- Subway Use Default -->
	<div id="subway_redirect_default_option_section" class="hidden subway-redirect-option-section">
		<p>
			<?php
				esc_html_e( "By choosing the default behavior, the redirect type will be set to the default WordPress' behavior. For example, if you have plugins like Peter's Login Redirect. This option will disable Subway's redirect and use the Peter's Login Redirect Instead.", 'subway' );
			?>
		</p>
	</div>

	<script>

		jQuery( document ).ready( function( $ ) {

			"use strict";

			subway_toggle_use_custom_page();    
			subway_toggle_use_custom_url();
			subway_toggle_use_default();

			$("#subway_use_page").on('click', function(){
				subway_toggle_use_custom_page();    
			});

			$("#subway_use_custom_url").on('click', function(){
				subway_toggle_use_custom_url();
			});

			$("#subway_use_default").on('click', function(){
				subway_toggle_use_default();
			});

			function subway_toggle_use_default() {

				if( $('#subway_use_default').is(':checked')) {

					$('#subway_redirect_custom_url_option_section').addClass('hidden');
					$('#subway_redirect_default_option_section').removeClass('hidden');
					$('#subway_redirect_page_option_section').addClass('hidden');
				}

				return;
			}

			function subway_toggle_use_custom_url() {

				if( $('#subway_use_custom_url').is(':checked')) { 
					
					$('#subway_redirect_custom_url_option_section').removeClass('hidden');
					$('#subway_redirect_default_option_section').addClass('hidden');
					$('#subway_redirect_page_option_section').addClass('hidden');

				}

				return;
			}

			function subway_toggle_use_custom_page() {

				if( $('#subway_use_page').is(':checked')) { 

					$('#subway_redirect_custom_url_option_section').addClass('hidden');
					$('#subway_redirect_default_option_section').addClass('hidden');
					$('#subway_redirect_page_option_section').removeClass('hidden');
				}

				return;
			}

		});
	</script>
	<?php
}
