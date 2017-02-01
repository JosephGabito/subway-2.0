<?php
/**
 * Plugin Name: Subway 2.0
 * Description: Subway is a plugin for WordPress that allows you hide the content of your website to non-logged in visitors and only displays them to logged in users. This plugin redirects the users to the provided login page with a login form that allows them to type their username/email and password combination.
 * Version: 2.0
 * Author: Dunhakdis
 * Author URI: http://dunhakdis.com
 * Text Domain: subway
 * License: GPL2
 *
 * Includes all the file necessary for Subway.
 *
 * PHP version 5
 *
 * @since     1.0
 * @package subway
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

// Define Subway Plugin Version.
define( 'SUBWAY_VERSION', '2.0' );

// Define Subway Directory Path.
define( 'SUBWAY_DIR_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

// Include Subway Settings Class.
require_once SUBWAY_DIR_PATH . 'admin-settings.php';

// Include Subway Options Getter Class.
require_once SUBWAY_DIR_PATH . 'classes/subway-options.php';

// Include Page Redirect Class.
require_once SUBWAY_DIR_PATH . 'classes/subway-page-redirect.php';

// Include Admin Redirect Class.
require_once SUBWAY_DIR_PATH . 'classes/subway-wp-admin-redirect.php';

// Include Subway Shortcodes.
require_once SUBWAY_DIR_PATH . 'shortcodes/subway-shortcodes.php';

// Redirect (302) all front-end request to the login page.
add_action( 'wp', array( 'Subway\Page_Redirect', 'index') );

// Redirect the user when he/she visit wp-admin or wp-login.php.
add_action( 'init', array( 'Subway\Admin_Redirect', 'index' ) );

// Redirect (302) invalid login request to the login page.
// add_action( 'wp_login_failed', 'subway_redirect_login_handle_failure' );
// Redirect the user after successful logged in.
// add_filter( 'login_redirect', 'subway_redirect_user_after_logged_in', 10, 3 );

