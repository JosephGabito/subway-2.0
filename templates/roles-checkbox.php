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
 * @category Subway\Templates\RolesCheckbox
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/subway
 * @link     github.com/codehaiku/subway The Plugin Repository
 */

namespace Subway;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}
?>

<?php $editable_roles = get_editable_roles(); ?>

<?php // Remove administrator for editable roles. ?>
<?php unset( $editable_roles['administrator'] ); ?>
<div id="subway-editable-roles-<?php echo esc_attr( $args['id'] ); ?>" class="subway-editable-roles toggable">
	<dl>
	<?php foreach ( $editable_roles as $role_name => $role_info ) { ?>

		<?php do_action( 'before_roles_checkbox_item', $args, $role_info ); ?>
		<dt>
			<?php $field_id = $role_name . '-' . $args['name']; ?>

			<?php $checked = ''; ?>
			
			<?php if ( in_array( $role_name, (array) $args['saved_roles'] ) ) : ?>
				<?php $checked = 'checked'; ?>
			<?php endif; ?>

			<?php if ( ! is_array( $args['saved_roles'] ) ) : ?>
				<?php $checked = 'checked'; ?>
			<?php endif; ?>

			<label for="<?php echo esc_attr( $field_id ); ?>">
				<input type="checkbox"
					<?php echo esc_attr( $checked ); ?>
					name="<?php echo esc_attr( $args['name'] ); ?>[]" 
						id="<?php echo esc_attr( $field_id ); ?>"
							value="<?php echo esc_attr( $role_name ); ?>"
					/>
				<?php echo esc_html( $role_info['name'] ); ?>
			</label>

		</dt>
		
		<?php do_action( 'before_roles_checkbox_item', $args, $role_info ); ?>

	<?php } ?>
	</dl>
	<p class="howto">
		<?php esc_html_e( $args['howto'] ); ?>
	</p>
</div><!--.toggable-->
