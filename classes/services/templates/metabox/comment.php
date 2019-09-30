<?php
/**
 * This file is part of the Subway WordPress Plugin Package.
 * This file contains the class which handles the metabox of the plugin.
 *
 * (c) Joseph G <emailnotdisplayed@domain.ltd>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP Version 5.4
 *
 * @category Classes\Services\Templates\Metabox\Comment
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/subway-2.0
 * @link     github.com/codehaiku/subway-2.0 The Plugin Repository
 */

namespace Subway;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

global $post;

?>
<?php if ( 'open' !== $post->comment_status ) : ?>
<?php $hidden = 'hidden'; ?>
<p>
	<strong>
		<?php esc_html_e( 'Commenting is currently not allowed.', 'subway' ); ?>
	</strong>
</p>
<p class="howto">
	<?php esc_html_e( "Set 'Allow comments' in 'Discussion' section to allow comments.", 'subway' ); ?>
</p>
<?php endif; ?>
<div class="subway-access-type-wrap <?php echo esc_attr( $hidden ); ?>">
	<p>
		<label>
		<?php $access_type = get_post_meta( $post_id, 'subway_post_discussion_access_type', true ); ?>
		
		<input <?php checked( $access_type, '1', true ); ?> name="subway_post_discussion_access_type" type="checkbox" value="1" />
			<?php esc_html_e( 'Limit to roles or membership types', 'subway' ); ?>
			<p class="howto">
				<?php esc_html_e( 'Check to limit commenting to specific roles. Use WordPress\'s discussion settings to disable comment for everyone.', 'subway' ); ?>
			</p>
	</label>
	</p>
	<h4>
		<?php esc_html_e( 'Allowed Roles', 'subway' ); ?>
	</h4>
	<div class="roles-checkbox-wrap">
		<?php
			$args = array();
			$args['name'] = 'subway_post_discussion_roles';
			$args['id'] = 'discussion-roles';
			$args['saved_roles'] = get_post_meta( $post_id, 'subway_post_discussion_roles', true );
			$args['howto'] = esc_html__( 'Limit commenting for this post only for checked user roles.', 'subway' );
		?>
		<?php Helpers::displayRolesCheckboxes( $args ); ?>
	</div>
</div>
