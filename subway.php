<?php
/**
 * Plugin Name: Subway
 * Description: Subway is a plugin for WordPress that allows you hide the content of your website to non-logged in visitors and only displays them to logged in users. This plugin redirects the users to the provided login page with a login form that allows them to type their username/email and password combination.
 * Version: 2.0.6
 * Author: Dunhakdis
 * Author URI: http://dunhakdis.com
 * Text Domain: subway
 * License: GPL2
 *
 * Includes all the file necessary for Subway.
 *
 * PHP version 5.4+
 *
 * @category Subway\Bootstrap
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/subway
 * @link     github.com/codehaiku/subway The Plugin Repository
 */

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

// Terminate Subway for PHP version 5.3.0 and below.
if ( version_compare( PHP_VERSION, '5.3.0', '<' ) ) {
	add_action( 'admin_notices', 'subway_admin_notice' );
	/**
	 * Displays admin notifications if the installed PHP version is less than 5.3.0
	 *
	 * @return void
	 */
	function subway_admin_notice() {
	?>
		<div class="notice notice-error is-dismissible">
	        <p>
	        	<strong>
	        		<?php esc_html_e( 'Notice: Subway uses PHP Class Namespaces 
	        		which is only available in servers with PHP 5.3.0 version and above. 
	        		Update your server\'s PHP version. You can deactivate 
	        		Subway in the meantime.', 'subway' ); ?>
	        	</strong>
	        </p>
	    </div>
	<?php }
	return;
}

// Define Subway Plugin Version.
define( 'SUBWAY_VERSION', '2.0' );

// Define Subway Directory Path.
define( 'SUBWAY_DIR_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

// Define Subway URL Path.
define( 'SUBWAY_DIR_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

// Include Subway i18n.
require_once SUBWAY_DIR_PATH . 'i18.php';

// Include Subway Settings Class.
require_once SUBWAY_DIR_PATH . 'admin-settings.php';

// Include Subway Helpers.
require_once SUBWAY_DIR_PATH . 'classes/subway-helpers.php';

// Include Subway Options Getter Class.
require_once SUBWAY_DIR_PATH . 'classes/subway-options.php';

// Include Page Redirect Class.
require_once SUBWAY_DIR_PATH . 'classes/subway-page-redirect.php';

// Include Admin Redirect Class.
require_once SUBWAY_DIR_PATH . 'classes/subway-auth-redirect.php';

// Include our scripts class.
require_once SUBWAY_DIR_PATH . 'classes/subway-enqueue.php';

// Include Subway Shortcodes.
require_once SUBWAY_DIR_PATH . 'shortcodes/subway-shortcodes.php';

// Redirect (302) all front-end request to the login page.
add_action( 'wp', array( 'Subway\PageRedirect', 'index' ) );

// Redirect (302) invalid login request to the login page.
add_action( 'wp_ajax_nopriv_subway_logging_in', array( 'Subway\AuthRedirect', 'handleAuthentication' ) );

// Load our JS and CSS files.
add_action( 'wp_enqueue_scripts', array( 'Subway\Enqueue', 'registerJs' ) );

// Change the default login url to our sign-in page.
add_filter( 'login_url', array( 'Subway\AuthRedirect', 'loginUrl' ), 10, 3 );

// Redirect the user after successful logged in to the right page.
// Does not trigger when using ajax form. Only on default wp-login.php and wp_login_form().
add_filter( 'login_redirect', array( 'Subway\AuthRedirect', 'getLoginRedirectUrl' ), 10, 3 );

// Change the default logout url to our sign-in page.
add_action( 'wp_logout', array( 'Subway\AuthRedirect', 'logoutUrl' ), 10, 3 );
