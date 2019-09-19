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
 * @category Subway\Options
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
 * Subway Option Methods.
 *
 * @category Subway\Options
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/subway The Plugin Repository
 * @since    1.0  
 */
final class Options
{

    /**
     * Get the redirect page url.
     *
     * @return mixed The redirect url of our settings. Otherwise, false.
     */
    public static function getRedirectPageUrl() 
    {

        $selected_login_post_id = intval( get_option('subway_login_page') );

        // Redirect logged in user to different page.
        if ( is_user_logged_in() )
        {
            if ( ! empty( intval( get_option('subway_logged_in_user_no_access_page') ) ) )
            {
                $selected_login_post_id = intval( get_option('subway_logged_in_user_no_access_page') );
            }
        }

        $login_url = site_url( 'wp-login.php', 'login' );

        $destination_post = get_post( $selected_login_post_id );

        if (! empty( $destination_post ) ) {

            $login_url = trailingslashit(get_permalink($destination_post->ID));

        }
        
        return add_query_arg( apply_filters( 'subway_redirected_url', 
            array('_redirected'=> 'redirected') ), $login_url );

    }

    /**
     * Fetches the public post ids.
     *
     * @return array The collection of public 'post' IDs.
     */
    public static function getPublicPostsIdentifiers() 
    {

        $subway_public_post = get_option('subway_public_post');

        $excluded_pages_collection = array();

        if (! empty($subway_public_post) ) {

            $excluded_pages_collection = explode(',', $subway_public_post);

        }

        // Should filter it by integer, spaces will be ignored, other strings.
        // Will be converted to zero '0'.
        return array_filter(array_map('intval', $excluded_pages_collection));

    }

    /**
     * Check if site is public or not.
     *
     * @return boolean True on success. Otherwise, false.
     */
    public static function isPublicSite() 
    {

        $subway_public_post = get_option('subway_is_public');

        if (! empty($subway_public_post) ) {

            return true;

        }
        return false;
    }

    public static function getPostNoAccessType( $post_id )
    {
        $allowed_no_access_type = array('block_content', 'redirect');

        $post_no_access_type = get_post_meta($post_id, 'subway-visibility-settings-no-access-type', true );
        
        if ( empty( $post_no_access_type ) || ! in_array( $post_no_access_type, $allowed_no_access_type ) ) 
        {
            $post_no_access_type = 'block_content';
        }

        return $post_no_access_type;
    }
}
