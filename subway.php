<?php
/**
 * Plugin Name:  Subway Memberships & Subscriptions
 * Description: It helps you build a membership website with out spending thousands of bucks. Lock content base on user roles or subscription types. Take control of your exclusive content.
 * Version: 3.0
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
 * (c) Subway Membership & Subscription
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package Subway
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

// Define Subway Plugin Version.
define( 'SUBWAY_VERSION', '3.0' );

// Define Subway Directory Path.
define( 'SUBWAY_DIR_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

// Define Subway URL Path.
define( 'SUBWAY_DIR_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

// Include Our API.
require_once SUBWAY_DIR_PATH . 'api/api.php';

// Include DB Migration.
require_once SUBWAY_DIR_PATH . 'install.php';

register_activation_hook( __FILE__, 'memberships_migrate' );

// Include Subway i18n.
require_once SUBWAY_DIR_PATH . 'i18.php';

// Include Vendors.
require_once SUBWAY_DIR_PATH . 'vendor/autoload.php';

// Include Subway Settings Class.
require_once SUBWAY_DIR_PATH . 'admin-settings.php';

// Include Subway Helpers.
require_once SUBWAY_DIR_PATH . 'classes/subway-helpers.php';

// Include Subway Options Getter Class.
require_once SUBWAY_DIR_PATH . 'classes/subway-options.php';

// Include Page Redirect Class.
require_once SUBWAY_DIR_PATH . 'classes/subway-auth-service.php';

// Include Admin Redirect Class.
require_once SUBWAY_DIR_PATH . 'classes/subway-auth-redirect.php';

// Include our scripts class.
require_once SUBWAY_DIR_PATH . 'classes/subway-enqueue.php';

// Include our scripts class.
require_once SUBWAY_DIR_PATH . 'classes/subway-metabox.php';

// Include Subway Shortcodes.
require_once SUBWAY_DIR_PATH . 'shortcodes/subway-shortcodes.php';

// Start loading components.
add_action('init', array( 'Subway\AuthService', 'start') );

// Ajax listener for handling user login.
add_action( 'wp_ajax_nopriv_subway_logging_in', array( 'Subway\AuthRedirect', 'handleAuthentication' ) );

// Load our JS and CSS files.
add_action( 'wp_enqueue_scripts', array( 'Subway\Enqueue', 'registerJs' ) );

// Change the default login url to our sign-in page.
add_filter( 'login_url', array( 'Subway\AuthRedirect', 'loginUrl' ), 10, 3 );

// Redirect the user after successful logged in to the right page.
// Does not trigger when using ajax form. Only on default wp-login.php and wp_login_form().
add_filter( 'login_redirect', array( 'Subway\AuthRedirect', 'getLoginRedirectUrl' ), 10, 3 );

// Adds the Subvway metabox.
add_action( 'plugins_loaded', array( 'Subway\Metabox', 'initMetabox' ) );

