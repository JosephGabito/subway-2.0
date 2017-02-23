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

	echo '<p class="description">' . sprintf( esc_html__( 'Select a login page and save the changes to make your site private. Leave blank to make your site public. %1$s.', 'subway' ), '<span style="font-weight: bold; color: #e53935;">' . esc_html__( 'You need to add "[subway_login]" shortcode in the selected page to show the login form (this will be done automatically after saving)', 'subway' ) ) . '</span></p>';

	return;
}
