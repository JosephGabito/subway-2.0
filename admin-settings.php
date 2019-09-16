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
 * @category Subway\Admin\Settings
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/subway
 * @link     github.com/codehaiku/subway The Plugin Repository
 */

namespace Subway;

if (! defined('ABSPATH') ) {
    return;
}

/**
 * Registers all the admin settings inside Settings > Subway
 *
 * @category Subway\Admin\Settings
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/subway The Plugin Repository
 * @since    1.0
 */
final class AdminSettings
{

    /**
     * Our class constructor
     */
    public function __construct()
    {

        add_action('admin_menu', array( $this, 'adminMenu' ));

        add_action('admin_init', array( $this, 'registerSettings' ));

    }

    /**
     * Display 'Subway' link under 'Settings'
     *
     * @return void
     */
    public function adminMenu()
    {

        // Add top-level menu "Membership".
        add_menu_page(
            esc_html__('Memberships Settings', 'subway'),
            esc_html__('Memberships', 'subway'),
            'manage_options',
            'subway-membership',
            array( $this, 'test'),
            'dashicons-clipboard',
            2
        );

        // Add 'general' sub menu page.
        add_submenu_page( 
            'subway-membership', 
            esc_html__('Memberships: General Settings', 'subway'), 
            esc_html__('General Settings', 'subway'), 
            'manage_options', 
            'subway-membership-general', 
            array( $this, 'optionsPageGeneral' )
        );

         // Add 'Payment Gateways' sub menu page.
        add_submenu_page( 
            'subway-membership', 
            esc_html__('Memberships: Payment Gateways', 'subway'), 
            esc_html__('Payment Gateways', 'subway'), 
            'manage_options', 
            'subway-membership-emails', 
            array( $this, 'general_cb' )
        );

        // Add 'Reports' sub menu page.
        add_submenu_page( 
            'subway-membership', 
            esc_html__('Memberships: Reports', 'subway'), 
            esc_html__('Reports', 'subway'), 
            'manage_options', 
            'subway-membership-emails', 
            array( $this, 'general_cb' )
        );

        // Add 'Emails' sub menu page.
        add_submenu_page( 
            'subway-membership', 
            esc_html__('Memberships: Email Settings', 'subway'), 
            esc_html__('Emails', 'subway'), 
            'manage_options', 
            'subway-membership-emails', 
            array( $this, 'general_cb' )
        );

        // Add 'BuddyPress' sub menu page.
        add_submenu_page( 
            'subway-membership', 
            esc_html__('Memberships: BuddyPress', 'subway'), 
            esc_html__('BuddyPress', 'subway'), 
            'manage_options', 
            'subway-membership-emails', 
            array( $this, 'general_cb' )
        );

        // Add 'bbPress' sub menu page.
        add_submenu_page( 
            'subway-membership', 
            esc_html__('Memberships: bbPress', 'subway'), 
            esc_html__('bbPress', 'subway'), 
            'manage_options', 
            'subway-membership-emails', 
            array( $this, 'general_cb' )
        );

        // Add 'Manual' sub menu page.
        add_submenu_page( 
            'subway-membership', 
            esc_html__('Memberships: Manual', 'subway'), 
            esc_html__('Documentation', 'subway'), 
            'manage_options', 
            'subway-membership-emails', 
            array( $this, 'general_cb' )
        );

        add_options_page(
            'Subway Settings', 'Subway', 'manage_options',
            'subway', array( $this, 'optionsPageGeneral' )
        );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueueSettingsScripts' ) );

        return;
    }

    public function test()
    {
        echo 'tae';
    }

    public function general_cb()
    {
        echo 'general';
    }

    /**
     * Registers all settings related to Subway.
     *
     * @return void
     */
    public function registerSettings()
    {

        // Register our settings section.
        add_settings_section(
            'subway-page-visibility-section', __('General', 'subway'),
            array( $this, 'sectionCallback' ), 'subway-settings-section'
        );

        // Register Redirect Options pages.
        add_settings_section(
            'subway-redirect-section', __('Redirect Options', 'subway'),
            array( $this, 'redirectCallback' ), 'subway-settings-section'
        );

         // Register Redirect Options pages.
        add_settings_section(
            'subway-messages-section', __('Messages', 'subway'),
            array( $this, 'messagesCallback' ), 'subway-settings-section'
        );

        // Register the fields.
        $fields = array(
          
            array(
                'id' => 'subway_login_page',
                'label' => __('Login Page', 'subway'),
                'callback' => 'subway_login_page_form',
                'section' => 'subway-settings-section',
                'group' => 'subway-page-visibility-section',
				'args'  => array(
					'label_for' => 'subway_login_page',
					'class'     => 'subway_login_page-option',
				),
            ),
            array(
                'id' => 'subway_redirect_type',
                'label' => __('Redirect Type', 'subway'),
                'callback' => 'subway_redirect_option_form',
                'section' => 'subway-settings-section',
                'group' => 'subway-redirect-section',
				'args'  => array(
					'label_for' => 'subway_redirect_type',
					'class'     => 'subway_redirect_type-option ',
				),
            ),
            array(
                'id' => 'subway_redirect_wp_admin',
                'label' => __('WP Login Link', 'subway'),
                'callback' => 'subway_lock_wp_admin',
                'section' => 'subway-settings-section',
                'group' => 'subway-redirect-section',
				'args'  => array(
					'label_for' => 'subway_redirect_wp_admin',
					'class'     => 'subway_redirect_wp_admin-option ',
				),
            ),
            array(
                'id' => 'subway_partial_message',
                'label' => __('Partial Block Message', 'subway'),
                'callback' => 'subway_messages',
                'section' => 'subway-settings-section',
                'group' => 'subway-messages-section',
                'args'  => array(
                    'label_for' => 'subway_messages',
                    'class'     => 'subway_messages-option ',
                ),
            ),
            array(
                'id' => 'subway_redirected_message_login_form',
                'label' => __('Login Form Message', 'subway'),
                'callback' => 'subway_redirected_message_login_form',
                'section' => 'subway-settings-section',
                'group' => 'subway-messages-section',
                'args'  => array(
                    'label_for' => 'subway_redirected_message_login_form',
                    'class'     => 'subway_messages-option ',
                ),
            ),
        );

        foreach ( $fields as $field ) {

            add_settings_field(
                $field['id'], $field['label'],
                $field['callback'], $field['section'],
                $field['group'], $field['args']
            );

            register_setting('subway-settings-group', $field['id']);

            $file = str_replace('_', '-', $field['callback']);

            include_once trailingslashit(SUBWAY_DIR_PATH) .
            'settings-fields/field-' . sanitize_title($file) . '.php';

        }

        // Register Redirect Page ID Settings.
        register_setting('subway-settings-group', 'subway_redirect_page_id');

        // Register Redirect Custom URL Settings.
        register_setting('subway-settings-group', 'subway_redirect_custom_url');

		$this->registerSettingsScripts();

        return;
    }

    /**
     * Callback function for the first Section.
     *
     * @return void
     */
    public function sectionCallback()
    {
        echo esc_html_e(
            'All settings related to the
        	visibility of your site and pages.', 'subway'
        );
        return;
    }

    public function messagesCallback() {
        return;
    }
    /**
     * Callback function for the second Section.
     *
     * @return void
     */
    public function redirectCallback()
    {
        return;
    }

    /**
     * Renders the 'wrapper' for our options pages.
     *
     * @return void
     */
    public function optionsPageGeneral()
    {
        ?>

        <div class="wrap">
            <h2>
                <?php esc_html_e('General Settings', 'subway'); ?>
             </h2>
             <form id="subway-settings-form" action="options.php" method="POST">
                <?php settings_fields('subway-settings-group'); ?>
                <?php do_settings_sections('subway-settings-section'); ?>
                <?php submit_button(); ?>
             </form>
        </div>

        <?php
    }

	/**
     * Registers the scipts for the Subway settings page.
     *
	 * @since  2.0.9
     * @access public
     * @return void
     */
	public function registerSettingsScripts() {
		// Registers the Subway settings Javascript.
	    wp_register_script( 'subway-settings-script', plugins_url('/assets/js/settings.js', __FILE__) );
	    wp_register_style( 'subway-settings-style', plugins_url('/assets/css/settings.css', __FILE__) );
	 }

	/**
     * Loads the scipts for the Subway settings page.
	 *
	 * @since  2.0.9
     * @access public
     * @return void
     * @return void
     */
	public function enqueueSettingsScripts( $hook ) {

		// Checks if page hook is Subway settings page.
		if ( in_array( $hook, array('settings_page_subway', 'widgets.php' ) ) ) {
			// Enqueues the script only on the Subway Settings page.
			wp_enqueue_script( 'subway-settings-script' );
			wp_enqueue_style( 'subway-settings-style' );
		}
	}
}

$subwaySettings = new AdminSettings();
