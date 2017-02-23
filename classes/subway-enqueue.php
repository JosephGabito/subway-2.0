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
 * @category Subway\Enqueue
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
 * Registers all stylesheet and javascript documents.
 *
 * @category Subway\Enqueue
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/subway The Plugin Repository
 * @since    1.0  
 */
final class Enqueue
{

    /**
     * Registers our CSS and Javascript to WordPress Enqueue Handler.
     *
     * @return void
     */
    public static function registerJs() 
    {

        $post_id = absint(get_queried_object_id());

        $signin_page = absint(get_option('subway_login_page'));

        // Only load the stylesheet and javascript documents inside our sign-in page.
        if ($post_id === $signin_page ) {

            wp_enqueue_style(
                'subway-style', 
                SUBWAY_DIR_URL . 'assets/css/subway.css'
            );

            if (! is_user_logged_in() ) {

                wp_enqueue_script(
                    'subway-script', 
                    SUBWAY_DIR_URL . 'assets/js/subway.js', 
                    array( 'jquery' )
                );

                wp_localize_script(
                    'subway-script', 'subway_config', array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'login_http_error' => esc_html__(
                        'An error occured while 
                    	transmitting the data. Refresh the page and try again', 
                        'subway'
                    ),
                    )
                );

            }
        }

        return;

    }

}
