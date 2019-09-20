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
 * @category Subway\SettingsFields
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/subway
 * @link     github.com/codehaiku/subway The Plugin Repository
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

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