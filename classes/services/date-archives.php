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
 * @category Subway\Auth\DateArchives
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
 * @category Subway\Auth\DateArchives
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/subway The Plugin Repository
 * @since    1.0
 */
final class DateArchives 
{
	
	public function __construct()
	{
		add_action('wp', array($this, 'redirectUser'));
		
		return;
	}

	public function redirectUser()
	{
		if ( current_user_can('manage_options') )
		{
			return;
		}

		if ( is_date() )
		{

			if ( $this->isDateArchiveLock() )
			{
				$login_url = \Subway\Options::getRedirectPageUrl();
				wp_safe_redirect( $login_url, 302 );
				exit;
			}
		}

		return;
	}

	public function getDateArchiveAccessType()
	{
		return apply_filters('set_date_archive_default_access_type', 'public');
	}

	public function isDateArchiveLock()
	{
		$option_is_date_archive_locked = false;

		$date_archive_access_type = $this->getDateArchiveAccessType();

		if ( 'private' === $date_archive_access_type ) 
		{
			$option_is_date_archive_locked = true;

			$current_user_role = Metabox::getUserRole( get_current_user_id() );

			$allowed_user_roles = (array)get_option('subway_options_date_archives_allowed_rules', array());

			if ( array_intersect( $current_user_role, $allowed_user_roles ) ) 
			{
				// Unlock the archive page.
				$option_is_date_archive_locked = false;
			}
			
		}

		return apply_filters('is_author_date_lock', $option_is_date_archive_locked );

	}
	
}

new DateArchives();