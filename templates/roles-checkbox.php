<?php
/*
 * This is a template file for roles checkbox found everywhere
 */
?>
<?php $editable_roles = get_editable_roles(); ?>

<?php // Remove administrator for editable roles. ?>
<?php unset( $editable_roles['administrator'] ); ?>
<div id="subway-editable-roles-<?php echo esc_attr( $args['id'] ); ?>" class="subway-editable-roles toggable">
	<dl>
	<?php foreach ( $editable_roles as $role_name => $role_info ) { ?>

		<?php do_action('before_roles_checkbox_item', $args, $role_info); ?>
		<dt>
			<?php $field_id = $role_name . '-' . $args['name']; ?>

			<?php $checked = ''; ?>
			
			<?php if( in_array( $role_name, (array) $args['saved_roles'] ) ): ?>
				<?php $checked = 'checked'; ?>
			<?php endif; ?>

			<?php if( ! is_array( $args['saved_roles'] ) ): ?>
				<?php $checked = 'checked'; ?>
			<?php endif; ?>

			<label for="<?php echo esc_attr( $field_id ); ?>">
				<input type="checkbox"
					<?php echo esc_attr($checked); ?>
					name="<?php echo esc_attr( $args['name'] ); ?>[]" 
						id="<?php echo esc_attr( $field_id ); ?>"
							value="<?php echo esc_attr( $role_name ); ?>"
					/>
				<?php echo esc_html( $role_info['name'] ); ?>
			</label>

		</dt>
		
		<?php do_action('before_roles_checkbox_item', $args, $role_info ); ?>

	<?php } ?>
	</dl>
	<p class="howto">
		<?php esc_html_e( $args['howto']); ?>
	</p>
</div><!--.toggable-->