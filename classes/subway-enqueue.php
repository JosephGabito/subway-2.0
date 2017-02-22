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

namespace Subway;

final class Enqueue {
	
	public static function register_js() {

   		wp_enqueue_style( 'subway-style', SUBWAY_DIR_URL . 'assets/css/subway.css' );

   		wp_enqueue_script( 'subway-script', SUBWAY_DIR_URL . 'assets/js/subway.js', array('jquery') );

   		wp_localize_script( 'subway-script', 'subway_config', array( 
   				'ajax_url' => admin_url( 'admin-ajax.php' ),
   				'login_http_error' => __( 'An error occured while transmitting the data. Refresh the page and try again', 'subway' )
   			)
   		);

   		return;
   		
	}

}