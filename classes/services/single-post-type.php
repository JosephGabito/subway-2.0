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
 * This class contains methods for deciding if 
 * it will display the content for current session or not. 
 *
 * @category Subway\Classes\Services\SinglePostTypeService
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/subway The Plugin Repository
 * @since    1.0
 */
final class SinglePostTypeService {

	public function __construct()  
	{
		add_action('the_content', array( $this, 'singlePostTypeContent'));
		add_action('wp', array( $this, 'singlePostTypeRedirect'));
	}

	public function singlePostTypeRedirect()
	{	
		$current_page_id = get_queried_object_id();

		$is_post_type_redirect = Metabox::isPostTypeRedirect( $current_page_id );

		$login_page_id = intval( get_option('subway_login_page') );

		$login_page_url = Options::getRedirectPageUrl();

		// Only run on main query.
		if ( ! is_singular() ) 
		{
			return;
		} 
		
		if( ! $is_post_type_redirect ) {
			return;
		}
		// Prevent infinite loop.
		if( $current_page_id === $login_page_id ) 
		{
			return;
		}

		if ( ! Metabox::isCurrentUserSubscribedTo( $current_page_id ) )
		{
			
			wp_safe_redirect($login_page_url, 302);

			exit;
		}

		return;
		
	}

	public function singlePostTypeContent( $content )
	{
		// Only run on main query.
		if ( ! is_singular() && is_main_query() && ! is_feed() ) 
		{
			return $content;
		}

		$post_id = get_queried_object_id();

		if ( ! Metabox::isCurrentUserSubscribedTo( $post_id ) )
		{

			$access_block_message = get_post_meta( $post_id, 'subway-visibility-settings-no-access-type-message', true );
			if ( ! empty ( $access_block_message ) ) 
			{
				$wrap_start = '<div class="widget-subway-no-access-message">';
				$wrap_end = '</div>';
				return $wrap_start . wp_kses_post( $access_block_message ) . $wrap_end;
			}
		}

		return $content;
	}

}

new SinglePostTypeService();