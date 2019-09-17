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
 * @category Subway\Helpers
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
 * Subway helper methods.
 *
 * @category Subway\Helpers
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/subway The Plugin Repository
 * @since    1.0  
 */
final class Helpers
{

    public function __construct()
    {
        return;
    }
    /**
     * Exit wrapper.
     * 
     * @return void
     */
    public static function close() 
    {
        exit;
    }

    public static function displayRolesCheckboxes( $args )
    {
        $post_id = get_the_id();
       
        $defaults = array(
                'name' => '',
                'option_name' => ''
            );

        $args = wp_parse_args( $args, $defaults );

        require SUBWAY_DIR_PATH . 'templates/roles-checkbox.php';
    }
}
