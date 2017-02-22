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
