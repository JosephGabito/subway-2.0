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
 * @category Subway\Auth\Comments
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
 * @category Subway\Auth\Comments
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/subway The Plugin Repository
 * @since    1.0
 */
final class Comments 
{
	public function __construct()
	{
		
		add_filter('comments_open', array( $this, 'restrictComment'), 10, 2 );
		add_action('comment_form_comments_closed', array( $this, 'displayMessage' ) );

		return;

	}

	public function currentUserCanComment()
	{
		$requested_post_id = filter_input( INPUT_POST, 'comment_post_ID', FILTER_VALIDATE_INT);
		
		$post_id = get_the_id();

		if ( ! empty ( $requested_post_id ) )
		{
			$post_id = $requested_post_id;
		}

		if ( current_user_can( 'manage_option' ) ) {
			return true;
		}

		$current_user_roles = MetaBox::getUserRole( get_current_user_id() );

		$allowed_user_roles = get_post_meta( $post_id, 'subway_post_discussion_roles', true );

		if ( array_intersect( $current_user_roles, (array)$allowed_user_roles ) )
		{
			return true;
		}

		
		return false;

	}

	public function displayMessage()
	{
		
		if ( ! $this->currentUserCanComment() )
		{
			echo '<div class="subway-comment-closed">';
				echo 'Your current role limits you to reading comments only.';
			echo '</div>';
		}

	}
	public function restrictComment()
	{

		if ( current_user_can( 'manage_option' ) ) 
		{
			return true;
		}

		if ( $this->currentUserCanComment()) 
		{

			return true;
		}

		return false;
	}
	
}

new Comments();
