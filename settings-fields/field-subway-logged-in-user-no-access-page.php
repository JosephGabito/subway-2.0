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
 * @category Subway\SettingsFields
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/subway
 * @link     github.com/codehaiku/subway The Plugin Repository
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

function subway_logged_in_user_no_access_page() {

	$subway_logged_in_user_no_access_page = intval( get_option( 'subway_logged_in_user_no_access_page' ) );

	if ( ! empty( $subway_logged_in_user_no_access_page ) ) {

		$login_page_object = get_post( $subway_logged_in_user_no_access_page );

	}

	wp_dropdown_pages(
		array(
			'name' => 'subway_logged_in_user_no_access_page',
			'selected' => intval( $subway_logged_in_user_no_access_page ),
			'show_option_none' => esc_html__( '---', 'subway' ),
		)
	);

	echo '<p class="description">' . sprintf( esc_html__( "Select a page to be used as the redirect point for logged-in users that do not have access to the content", 'subway' ) ) . '</span></p>';

	return;
}