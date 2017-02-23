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

	<p class="description">
		<?php 
			echo sprintf( __( "In case, you were locked out. Use the link below to bypass the log-in page and go directly 
			to your website's wp-login URL (http://yoursiteurl.com/wp-login.php): <strong style='color: #e53935;'>%s</strong>", 'subway' ), site_url( 'wp-login.php?no_redirect=true' ) ); 
		?>
	</p>
<?php
return;
}
