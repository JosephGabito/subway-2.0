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
 * @category Subway\Shortcodes
 * @package  Subway\Shortcodes
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
 * Registers Plugin Shortcodes
 *
 * @category Subway\Shortcodes
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/subway The Plugin Repository
 * @since    1.0  
 */
final class Shortcodes
{

    /**
     * Class Constructor.
     *
     * @return void
     */
    private function __construct() 
    {
        
        add_action('init', array( $this, 'register'));
        
        return $this;

    }

    /**
     * Instantiate our class.
     * 
     * @return mixed The instance of this class.
     */
    public static function instance() 
    {
        
        static $instance = null;

        if (null === $instance ) {

            $instance = new Shortcodes();

        }

        return $instance;

    }

    /**
     * Instantiate our class.
     * 
     * @return void
     */
    public function register() 
    {

        add_shortcode('subway_login', array( $this, 'loginForm' ));

        add_action('login_form_middle', array( $this, 'loginFormAction' ), 10, 2);

        add_action('login_form_middle', array( $this, 'lostPasswordLink' ), 10, 2);

        return;

    }

    /**
     * Displays the login form
     * 
     * @return void
     */
    public function loginForm( $atts )
    {
        $atts = shortcode_atts( array(
            'echo'           => true,
            'form_id'        => 'loginform',
            'label_username' => __( 'Username', 'subway' ),
            'label_password' => __( 'Password', 'subway' ),
            'label_remember' => __( 'Remember Me', 'subway' ),
            'label_log_in'   => __( 'Log In', 'subway' ),
            'id_username'    => 'user_login',
            'id_password'    => 'user_pass',
            'id_remember'    => 'rememberme',
            'id_submit'      => 'wp-submit',
            'remember'       => true,
            'value_username' => '',
            'value_remember' => false,
            'redirect'       => home_url(),
        ), $atts );

        return $this->renderTemplate($atts, 'login-form.php');
    }

    /**
     * Include the specific plugin file if there is no template file.
     * 
     * @param mixed  $atts The shortcode attribute.
     * @param string $file The shortcode template file.
     * 
     * @return string The html template content.
     */
    protected function renderTemplate( $atts, $file = '' ) 
    {

        ob_start();

        if (empty($file) ) {
            
            return;

        }

        $template = SUBWAY_DIR_PATH . 'templates/'.$file;

        if (file_exists($template) ) {

            $theme_template = locate_template(array('gears/shortcodes/'.$file ));

            if ($theme_template) {

                   $template = $theme_template;

            }

            include $template;

        } else {

            echo sprintf(
                esc_html_e(
                    'Subway Error: Unable to find template file in: %1s', 'subway'
                ), 
                $template
            );

        }

        return ob_get_clean();
    }

    /**
     * The action for our login form.
     * 
     * @param string $__content The current filtered contents.
     * 
     * @return string            The content of our login form action.
     */
    public function loginFormAction( $__content ) 
    {

        ob_start();
        
        do_action('login_form');
        
        return $__content . ob_get_clean();

    }

     /**
     * The action for our 'lost password' link.
     * 
     * @param string $content The current filtered contents.
     * 
     * @return string          The content of our lost password link.
     */
    public function lostPasswordLink( $content ) 
    {
        
        return $content . $this->renderTemplate(
            array(), 
            'login-form-lost-password.php'
        );

    }

}

$subway_shortcode = Shortcodes::instance();
