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
?>
<p class="subway-login-lost-password">
	<a href="<?php echo esc_url( wp_lostpassword_url( $redirect = '' ) ); ?>">
	 	<?php esc_html_e( 'Forgot Password', 'subway' ); ?>
	</a>
</p>
