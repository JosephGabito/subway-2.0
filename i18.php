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
 * @category Subway\i18
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
 * Register Plugin i18 (internationalization)
 *
 * @category Subway\i18
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/subway The Plugin Repository
 * @since    1.0  
 */
final class I18
{

    /**
     * Class Constructor.
     *
     * @return void
     */
    public function __construct() 
    {

        add_action('plugins_loaded', array( $this, 'subwayLocalizePlugin' ));

        return;
    }

    /**
     * Subway l8n callback.
     *
     * @return void
     */
    public function subwayLocalizePlugin() 
    {

        load_plugin_textdomain('subway', false, basename( dirname( __FILE__ ) ) . '/languages'  );

        return;

    }

}

$subwayi18 = new I18();
