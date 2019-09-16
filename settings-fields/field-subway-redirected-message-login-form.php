<?php
function subway_redirected_message_login_form() { ?>
	
	<?php $content = get_option('subway_redirected_message_login_form',
		esc_html__('Members only page. Please use the login form below to access the page.','subway'));  ; ?>

	<?php $settings = apply_filters('subway_redirected_message_login_form_editor',
		array( 'teeny' => true, 'media_buttons' => true, 'editor_height' => 100 )); ?>

	<?php echo wp_editor( $content, 'subway_redirected_message_login_form', $settings ); ?>
	
	<?php
	return;
}