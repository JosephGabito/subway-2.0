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
        
        add_action( 'load-toplevel_page_subway-membership', array( $this, 'membership_screen_options' ) );

        add_filter( 'set-screen-option', array( $this, 'test_table_set_option' ), 10, 3);

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
            array( $this, 'membershipTable'),
            'dashicons-clipboard',
            2
        );

        // Add 'dashboard' sub menu page.
        add_submenu_page( 
            'subway-membership', 
            esc_html__('Memberships: Products', 'subway'), 
            esc_html__('Products', 'subway'), 
            'manage_options', 
            'subway-membership', 
            array( $this, 'membershipTable' )
        );

        // Add 'general' sub menu page.
        add_submenu_page( 
            'subway-membership', 
            esc_html__('Memberships: Settings', 'subway'), 
            esc_html__('Settings', 'subway'), 
            'manage_options', 
            'subway-membership-general', 
            array( $this, 'optionsPageGeneral' )
        );

         // Add 'Payment Gateways' sub menu page.
        add_submenu_page( 
            'subway-membership', 
            esc_html__('Memberships: Payment Gateways', 'subway'), 
            esc_html__('Payments', 'subway'), 
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

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueueSettingsScripts' ) );

        return;
    }

    public function membership_screen_options()
    {
        global $SubwayListTableMembership;
        
        $option = 'per_page';

        $args = array(
            'label' => 'Books',
            'default' => 10,
            'option' => 'books_per_page'
        );
        add_screen_option( $option, $args );

        if( ! class_exists( 'WP_List_Table' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
        }

        require_once SUBWAY_DIR_PATH . 'classes/subway-membership-list-table.php';

        $SubwayListTableMembership = new \Subway_List_table_Membership();

    }

    public function test_table_set_option( $status, $option, $value )
    {
        return $value;
    }
    public function membershipTable()
    {
        
        require_once SUBWAY_DIR_PATH . 'templates/admin-membership.php';
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
            'subway-page-visibility-section', __('Pages', 'subway'),
            array( $this, 'sectionCallback' ), 'subway-settings-section'
        );

        // Register Archives Options pages.
        add_settings_section(
            'subway-archives-section', __('Archives', 'subway'),
            array( $this, 'archivesCallback' ), 'subway-settings-section'
        );

        // Register Redirect Options pages.
        add_settings_section(
            'subway-redirect-section', __('Login Redirect', 'subway'),
            array( $this, 'redirectCallback' ), 'subway-settings-section'
        );

         // Register Redirect Options pages.
        add_settings_section(
            'subway-messages-section', __('Messages', 'subway'),
            array( $this, 'messagesCallback' ), 'subway-settings-section'
        );

        // Register the fields.
        $fields = array(
            
            // Login page settings.
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
            // Redirect page for logged-in users.
            array(
                'id' => 'subway_logged_in_user_no_access_page',
                'label' => __('No Access Page', 'subway'),
                'callback' => 'subway_logged_in_user_no_access_page',
                'section' => 'subway-settings-section',
                'group' => 'subway-page-visibility-section'
            ),
            // Author archive access settings.
            array(
                'id' => 'subway_author_archives',
                'label' => __('Author', 'subway'),
                'callback' => 'subway_author_archives',
                'section' => 'subway-settings-section',
                'group' => 'subway-archives-section'
            ),
            // Date archive access settings.
            array(
                'id' => 'subway_date_archives',
                'label' => __('Date', 'subway'),
                'callback' => 'subway_date_archives',
                'section' => 'subway-settings-section',
                'group' => 'subway-archives-section'
            ),
           
            // Login redirect type.
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
            // Login link notice.
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
            // Partial message settings.
            array(
                'id' => 'subway_partial_message',
                'label' => __('Partial Content Block', 'subway'),
                'callback' => 'subway_messages',
                'section' => 'subway-settings-section',
                'group' => 'subway-messages-section'
            ),
            // Commenting message.
            array(
                'id' => 'subway_comment_limited_message',
                'label' => __('Limited Comment', 'subway'),
                'callback' => 'subway_comment_limited_message',
                'section' => 'subway-settings-section',
                'group' => 'subway-messages-section'
            ),
            // Login form message settings.
            array(
                'id' => 'subway_redirected_message_login_form',
                'label' => __('Login Form', 'subway'),
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

        // Register Author Archive Settings.
        register_setting('subway-settings-group', 'subway_author_archives_roles');

        // Register Date Archive Settings.
        register_setting('subway-settings-group', 'subway_date_archives_roles');

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
        ?>
        <p class="howto subway-tooltip">
            <?php
            echo esc_html_e('Memberships needs a few pages to operate properly. Create and assign existing pages to each of the corresponding option.', 'subway');
            ?>
        </p>
        <?php
        return;
    }

    public function messagesCallback() {
        return;
    }

    public function archivesCallback()
    {
        ?>
        <p class="howto">
            <?php esc_html_e('WordPress contains default archives for Authors, Date, Custom Posts Types, and Custom Taxonomies. ', 'subway'); ?>
            <br/>
            <?php esc_html_e('You can choose the access type below for each archive.', 'subway'); ?>
        </p>
        <?php
        return;
    }
    /**
     * Callback function for the second Section.
     *
     * @return void
     */
    public function redirectCallback()
    {
        ?>
        <p class="howto subway-tooltip">
            <?php
                esc_html_e('Where do you want your members to go after logging-in? You can pick a page, a Custom URL, or just a WordPress Default behavior. Page and Custom URL has its settings.', 'subway'
                );
            ?>
        </p>
        <?php
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
      
		if ( in_array( $hook, array('memberships_page_subway-membership-general', 'widgets.php' ) ) ) {
			// Enqueues the script only on the Subway Settings page.
			wp_enqueue_script( 'subway-settings-script' );
			wp_enqueue_style( 'subway-settings-style' );
		}
	}
}

$subwaySettings = new AdminSettings();
