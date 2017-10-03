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
 * @category Subway\Auth\Redirect
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
 * @category Subway\Auth\Redirect
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/subway The Plugin Repository
 * @since    1.0
 */
final class AuthRedirect
{

    /**
     * Handles our ajax authentication.
     *
     * @return void
     */
    public static function handleAuthentication()
    {

        // Set the header type to json.
        header('Content-Type: application/json');

        $log = filter_input(INPUT_POST, 'log', FILTER_SANITIZE_STRING);

        $pwd = filter_input(INPUT_POST, 'pwd', FILTER_SANITIZE_STRING);

        if (empty($log) && empty($pwd) ) {

            $response['type'] = 'error';

            $response['message'] = esc_html__(
                'Username and Password cannot be empty.',
                'subway'
            );

        } else {

            $is_signin = wp_signon();

            $response = array();

            if (is_wp_error($is_signin) ) {

                $response['type'] = 'error';

                $response['message'] = $is_signin->get_error_message();

            } else {

                $response['type'] = 'success';

                $response['message'] = esc_html__(
                    'You have successfully logged-in. Redirecting you in few seconds...',
                    'subway'
                );

            }
        }

        $subway_redirect_url = AuthRedirect::getLoginRedirectUrl('', $is_signin);

        $response['redirect_url'] = apply_filters(
            'subway_login_redirect',
            $subway_redirect_url
        );

        echo wp_json_encode($response);

        wp_die();

    }

    /**
     * Returns the filtered redirect url for the current user.
     *
     * @param string $redirect_to The default redirect callback argument.
     * @param mixed  $user        The object/array of the logged-in user.
     *
     * @return string              The final redirect url.
     */
    public static function getLoginRedirectUrl( $redirect_to, $user )
    {

        $subway_redirect_type = get_option('subway_redirect_type');

        // Redirect the user to default behaviour.
        // If there are no redirect type option saved.
        if (empty($subway_redirect_type) ) {

            return $redirect_to;

        }

        if ('default' === $subway_redirect_type ) {
            return $redirect_to;
        }

        if ('page' === $subway_redirect_type ) {

            // Get the page url of the selected page.
            // If the admin selected 'Custom Page' in the redirect type settings.
            $selected_redirect_page = intval(get_option('subway_redirect_page_id'));

            // Redirect to default WordPress behaviour.
            // If the user did not select page.
            if (empty($selected_redirect_page) ) {

                return $redirect_to;
            }

            // Otherwise, get the permalink of the saved page
            // and let the user go into that page.
            return get_permalink($selected_redirect_page);

        } elseif ('custom_url' === $subway_redirect_type ) {

            // Get the custom url saved in the redirect type settings.
            $entered_custom_url = get_option('subway_redirect_custom_url');

            // Redirect to default WordPress behaviour
            // if the user did enter a custom url.
            if (empty($entered_custom_url) ) {

                return $redirect_to;

            }

            // Otherwise, get the custom url saved
            // and let the user go into that page.
            if (! empty($user->ID) ) {
                $entered_custom_url = str_replace(
                    '%user_id%', $user->ID,
                    $entered_custom_url
                );
            }

            if (! empty($user->user_login) ) {
                $entered_custom_url = str_replace(
                    '%user_name%', $user->user_login,
                    $entered_custom_url
                );
            }

            return $entered_custom_url;

        }

        // Otherwise, quit and redirect the user back to default WordPress behaviour.
        return $redirect_to;
    }

    /**
     * Callback function for the 'login_url' filter defined in Subway.php
     *
     * @param string $login_url The login url.
     *
     * @return string            The final login url.
     */
    public static function loginUrl( $login_url  )
    {

        $subway_login_page = Options::getRedirectPageUrl();

        // Return the default login url if there is no log-in page defined.
        if (empty($subway_login_page) ) {
            return $login_url;
        }

        // Otherwise, return the Subway login page.
        return $subway_login_page;

    }

    /**
     * The callback function for our logout filter.
     *
     * @return void
     */
    public static function logoutUrl()
    {

        $subway_login_page = Options::getRedirectPageUrl();

        wp_safe_redirect(esc_url($subway_login_page . '?loggedout=true'));

        Helpers::close();

    }

}
