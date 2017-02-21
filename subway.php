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

if ( version_compare( PHP_VERSION, '5.3.0', '<' ) ) {
	add_action( 'admin_notices', 'subway_admin_notice' );
	function subway_admin_notice() { ?>
		<div class="notice notice-error is-dismissible">
	        <p><strong><?php _e( 'Notice: Subway uses PHP Class Namespaces which is only available in servers with PHP 5.3.0 version and above. Update your server\'s PHP version. You can deactivate Subway in the meantime.', 'subway' ); ?></strong></p>
	    </div>
	<?php } 
	return;
}
// Define Subway Plugin Version.
define( 'SUBWAY_VERSION', '2.0' );

// Define Subway Directory Path.
define( 'SUBWAY_DIR_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

define( 'SUBWAY_DIR_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

// Include Subway i18n.
require_once SUBWAY_DIR_PATH . 'i18.php';

// Include Subway Settings Class.
require_once SUBWAY_DIR_PATH . 'admin-settings.php';

// Include Subway Options Getter Class.
require_once SUBWAY_DIR_PATH . 'classes/subway-options.php';

// Include Page Redirect Class.
require_once SUBWAY_DIR_PATH . 'classes/subway-page-redirect.php';

// Include Admin Redirect Class.
require_once SUBWAY_DIR_PATH . 'classes/subway-wp-admin-redirect.php';

// Include our scripts class.
require_once SUBWAY_DIR_PATH . 'classes/subway-enqueue.php';

// Include Subway Shortcodes.
require_once SUBWAY_DIR_PATH . 'shortcodes/subway-shortcodes.php';

// Redirect (302) all front-end request to the login page.
add_action( 'wp', array( 'Subway\Page_Redirect', 'index') );

// Redirect the user when he/she visit wp-admin or wp-login.php.
add_action( 'init', array( 'Subway\Admin_Redirect', 'index' ) );

// Redirect (302) invalid login request to the login page.
add_action( 'wp_ajax_nopriv_subway_logging_in', array('Subway\Admin_Redirect', 'handle_authentication') );

// Redirect the user after successful logged in; Priority = 10; Accepted Params Number = 3'
add_filter( 'login_redirect', array('Subway\Admin_Redirect', 'authentication_200'), 10, 3 );

// Load our JS and CSS files.
add_action( 'wp_enqueue_scripts', array('Subway\Enqueue', 'register_js') );
