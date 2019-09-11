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

	echo '<p class="description">' . sprintf( esc_html__( "Select a page to be used for logging in. A shortcode [subway_login] which will display the login form will be automatically added to the content of the selected page", 'subway' ) ) . '</span></p>';

	return;
}
