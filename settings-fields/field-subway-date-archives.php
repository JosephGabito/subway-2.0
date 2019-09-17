<?php
function subway_date_archives()
{
	?>
	<p>
		<?php $subway_date_archives = get_option( 'subway_date_archives', 'public' ); ?>
		<label>
			<input <?php checked($subway_date_archives, 'public', true); ?> type="radio" 
				name="subway_date_archives" 
					id="subway_date_archives_public" value="public" />
			<?php esc_html_e('Public', 'subway'); ?>
		</label> &nbsp;
		<label>
			<input <?php checked($subway_date_archives, 'private', true); ?> type="radio" 
				name="subway_date_archives" 
					id="subway_date_archives_private" value="private" />
			<?php esc_html_e('Private', 'subway'); ?>
		</label>
	</p>
	<p>
		<?php $editable_roles = get_editable_roles(); ?>
	
		<?php unset( $editable_roles['administrator'] ); ?>
		
		<?php $subway_date_archives_roles = get_option('subway_date_archives_roles'); ?>

		<?php foreach ( $editable_roles as $role_name => $role_info ): ?>
			
			<?php $checked = ''; ?>

			<?php if ( is_array( $subway_date_archives_roles ) ): ?>
				<?php if ( in_array( $role_name, $subway_date_archives_roles ) ): ?>
					<?php $checked = 'checked'; ?>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( ! $subway_date_archives_roles ) {?>
				<?php $checked = 'checked'; ?>
			<?php }?>

			<label class="subway-archive-roles">
				<input <?php echo esc_attr( $checked );?> type="checkbox" 
					name="subway_date_archives_roles[]"
						value="<?php echo esc_attr($role_name); ?>" />
				<?php echo esc_html( $role_info['name'] ); ?>
			</label>

		<?php endforeach; ?>
		
	</p>
	<?php
}