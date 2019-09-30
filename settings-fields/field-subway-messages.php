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

function subway_messages(){
	?>
	<?php $content = $message = get_option('subway_partial_message',esc_html__('Please login to see this content','subway'));  ; ?>

	<?php $settings = apply_filters('subway_partial_message_editor',
		array( 'teeny' => false, 'media_buttons' => true, 'editor_height' => 100 )); ?>
		
	<?php echo wp_editor( $content, 'subway_partial_message', $settings ); ?>
	<?php
	return;
}