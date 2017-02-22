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

final class Shortcodes {

	private function __construct() {
		
		add_action( 'init', array( $this, 'register') );
		
		return $this;

	}

	static function instance() {
		
		static $instance = null;

		if ( null === $instance ) {

			$instance = new Shortcodes();

		}

		return $instance;

	}


	function register() {

		add_shortcode( 'subway_login', array( $this, 'wp_login' ) );

		add_action( 'login_form_middle', array( $this, 'login_form_action' ), 10, 2 );

		add_action( 'login_form_middle', array( $this, '__action_lost_password_link' ), 10, 2 );

		return null;

	}

	function wp_login() {
		
		$atts = array();

		echo $this->get_template_file( $atts, $file = 'login-form.php', $content = null );

		return;
	}

	protected function get_template_file( $atts, $file = '', $content = null ) {

		ob_start();

		if ( empty( $file ) ) {
			
			return;

		}

		$template = SUBWAY_DIR_PATH . 'templates/'.$file;

		if ( file_exists( $template ) ) {

			if ( $theme_template = locate_template( array('gears/shortcodes/'.$file ) ) ) {

	        	$template = $theme_template;

	    	}

	    	include $template;

    	} else {

	    	echo sprintf( __( 'Subway Error: Unable to find template file in: %1s', 'subway' ), $template );

	    }

	    return ob_get_clean();
	}

	public function login_form_action( $__content ) {
		ob_start();
		do_action( 'login_form' );
		return $__content . ob_get_clean();
	}

	public function __action_lost_password_link( $__content ) {
		
		return $__content . $this->get_template_file( $params = array(),  $file = 'login-form-lost-password.php',  $content = null );
	}

}

$subway_shortcode = Shortcodes::instance();
?>