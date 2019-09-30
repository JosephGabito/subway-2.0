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
		
		$post_id = get_the_id();

		$access_type = get_post_meta( $post_id, 'subway_post_discussion_access_type', true );

		// Allow commenting when editor unchecked the 'Limit to roles or membership types'.
		if ( empty ( $access_type ) )
		{
			return apply_filters('subway_post_discussion_allow_comment', true);
		}
		// If access type is not empty. 
		// Editor limits the commenting.
		if ( ! empty ( $access_type ) )
		{
			// Allow administrator.
			if ( current_user_can( 'manage_option' ) ) 
			{
				return apply_filters('subway_post_discussion_allow_comment', true);
			}
			// Allow specific roles only.
			$current_user_roles = MetaBox::getUserRole( get_current_user_id() );

			$allowed_user_roles = get_post_meta( $post_id, 'subway_post_discussion_roles', true );

			if ( array_intersect( $current_user_roles, (array)$allowed_user_roles ) )
			{
				return apply_filters('subway_post_discussion_allow_comment', true);
			}

		}
		
		return apply_filters('subway_post_discussion_allow_comment', false);

	}

	public function displayMessage()
	{
		
		if ( ! $this->currentUserCanComment() )
		{
			echo '<div class="subway-comment-closed">';
				echo wp_kses_post( get_option('subway_comment_limited_message',esc_html__('Commenting is limited.','subway') ) );
			echo '</div>';
		}

	}
	public function restrictComment()
	{
		// Check if coming from submit form.
		$requested_post_id = filter_input( INPUT_POST, 'comment_post_ID', FILTER_VALIDATE_INT);

		// Handle comment submission.
		$post_id = get_the_id();
		// Post is coming from submit comment.
		if ( ! empty ( $requested_post_id ) ) {
			$post_id = $requested_post_id;
		}

		$current_post = get_post( $post_id );
		
     	if ( "open" === $current_post->comment_status ) 
     	{

			if ( current_user_can( 'manage_option' ) ) 
			{
				return true;
			}

			if ( $this->currentUserCanComment()) 
			{

				return true;
			}

		}

		return false;
	}
	
}

new Comments();
