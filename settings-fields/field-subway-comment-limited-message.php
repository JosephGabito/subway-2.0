<?php
function subway_comment_limited_message()
{
	?>

	<?php $content = get_option('subway_comment_limited_message',esc_html__('Commenting is limited.','subway'));  ; ?>

	<?php $settings = apply_filters('subway_comment_limited_message_editor',
		array( 'teeny' => false, 'media_buttons' => true, 'editor_height' => 100 )); ?>
		
	<?php echo wp_editor( $content, 'subway_comment_limited_message', $settings ); ?>

	<?php
	return;
}