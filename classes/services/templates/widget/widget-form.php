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
 * @category Classes\Services\Templates\Widgets\WidgetForm
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/subway-2.0
 * @link     github.com/codehaiku/subway-2.0 The Plugin Repository
 */
?>

<div id="#">

	<dl>
		<h4>
			<a href="#">
				<?php esc_html_e('Edit Membership Access', 'subway'); ?>&nbsp; &#9662;
			</a>
		</h4>
		<p>
			<label>
			<input <?php checked( $instance['subway-widget-access-type'], 'public', true ); ?> type="radio" id="<?php echo $widget->get_field_id('subway-widget-access-type'); ?>" name="<?php echo $widget->get_field_name('subway-widget-access-type'); ?>" value="public" />
				<?php esc_html_e('Public', 'subway'); ?>
			</label>
		</p>
		<p>
			<label>
			<input <?php checked( $instance['subway-widget-access-type'], 'private', true ); ?> type="radio" id="<?php echo $widget->get_field_id('subway-widget-access-type'); ?>" name="<?php echo $widget->get_field_name('subway-widget-access-type'); ?>" value="private" />
				<?php esc_html_e('Private', 'subway'); ?>
			</label>
		</p>
	</dl>
</div>