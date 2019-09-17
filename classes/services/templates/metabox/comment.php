<?php
/**
 * This is the template file for our comment meta box
 */
?>
<label>
	<input type="checkbox" />
		<?php esc_html_e('Limit to roles or membership types', 'subway'); ?>
</label>
<h4><?php esc_html_e('Allowed Roles', 'subway'); ?></h4>
<div class="roles-checkbox-wrap">
	<?php 
		$args = array();
		$args['name'] = 'subway_post_discussion_roles';
		$args['saved_roles'] = get_post_meta( $post_id, 'subway_post_discussion_roles', true );
		$args['howto'] = esc_html__('Limit commenting for this post only for checked user roles.', 'subway');
	?>
	<?php \Subway\Helpers::displayRolesCheckboxes( $args ); ?>
</div>	