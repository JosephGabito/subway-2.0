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

        add_options_page(
            'Subway Settings', 'Subway', 'manage_options', 
            'subway', array( $this, 'optionsPage' )
        );

        return;
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
            'subway-page-visibility-section', __('Pages Visibility', 'subway'), 
            array( $this, 'sectionCallback' ), 'subway-settings-section' 
        );

        // Register Redirect Options pages.
        add_settings_section(
            'subway-redirect-section', __('Redirect Options', 'subway'), 
            array( $this, 'redirectCallback' ), 'subway-settings-section' 
        );

        // Register the fields.
        $fields = array(
            array(
                'id' => 'subway_is_public',
                'label' => __('Public Website', 'subway'),
                'callback' => 'subway_is_public_form',
                'section' => 'subway-settings-section',
                'group' => 'subway-page-visibility-section',
            ),
    
            array(
                'id' => 'subway_login_page',
                'label' => __('Private Login Page', 'subway'),
                'callback' => 'subway_login_page_form',
                'section' => 'subway-settings-section',
                'group' => 'subway-page-visibility-section',
            ),
            array(
                'id' => 'subway_public_post',
                'label' => __('Public Posts IDs', 'subway'),
                'callback' => 'subway_public_post',
                'section' => 'subway-settings-section',
                'group' => 'subway-page-visibility-section',
            ),
    
            array(
                'id' => 'subway_redirect_type',
                'label' => __('Redirect Type', 'subway'),
                'callback' => 'subway_redirect_option_form',
                'section' => 'subway-settings-section',
                'group' => 'subway-redirect-section',
            ),
            array(
                'id' => 'subway_redirect_wp_admin',
                'label' => __('Bypassing <em>wp-login.php</em>', 'subway'),
                'callback' => 'subway_lock_wp_admin',
                'section' => 'subway-settings-section',
                'group' => 'subway-redirect-section',
            ),
        );

        foreach ( $fields as $field ) {

            add_settings_field(
                $field['id'], $field['label'], 
                $field['callback'], $field['section'], 
                $field['group']
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
    public function optionsPage() 
    {
        ?>

        <div class="wrap">
            <h2>
                <?php esc_html_e('Subway Settings', 'subway'); ?>
             </h2>
             <form id="subway-settings-form" action="options.php" method="POST">
                <?php settings_fields('subway-settings-group'); ?>
                <?php do_settings_sections('subway-settings-section'); ?>
                <?php submit_button(); ?>
             </form>
        </div>
        
        <?php
    }

}

$subwaySettings = new AdminSettings();
