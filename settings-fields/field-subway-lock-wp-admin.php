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

function subway_lock_wp_admin() { ?>

	<label for="subway_redirect_wp_admin">
		<input <?php echo checked( 1, get_option( 'subway_redirect_wp_admin' ), false ) ;?> value="1" name="subway_redirect_wp_admin" 
		id="subway_redirect_wp_admin" type="checkbox" class="code" /> 
		<?php esc_html_e( 'Check to hide /wp-admin or /wp-login.php to logged out users and redirect them to Subway custom login form.', 'subway' ); ?>
		
	</label>

	<p class="description">
		<?php 
			echo sprintf( __( "<br/>In case, you were locked out. Use the link below to bypass the log-in page and go directly 
			to your website's wp-login URL (http://yoursiteurl.com/wp-login.php):
			<br> <strong>%s</strong>", 'subway' ), site_url( 'wp-login.php?no_redirect=true' ) ); 
		?>
	</p>
<?php
return;
}
