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
 * @category Subway\Auth\Redirect
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/subway
 * @link     github.com/codehaiku/subway The Plugin Repository
 */

namespace Subway;

if (! defined('ABSPATH') ) {
    return;
}

/**
 * This class contains methods for deciding if 
 * it will display the content of widget. 
 *
 * @category Subway\Classes\Services\WidgetService
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/subway The Plugin Repository
 * @since    1.0
 */
final class WidgetService {

	var $allowed_access_type = array('public', 'private');

	public function __construct()
	{
		// load the widget option for each widgets.
		add_action('in_widget_form', array( $this, 'loadWidgetOptions' ), 10, 3);
		// Handle the updating of the widget options.
		add_action('widget_update_callback', array( $this, 'saveWidgetOptions'), 10, 2);
		// Control the display of the widget.
		add_filter('widget_display_callback', array( $this, 'authorizeUserWidget'), 10, 3 );

		return;
	}

	public function authorizeUserWidget( $settings, $widget, $args )
	{	
		// Show all widgets to administrator.
		if ( current_user_can('manage_options') )
		{
			return true;
		}
		// Show all widgets that don't have access types.
		if ( ! isset( $settings['subway-widget-access-type'] ) ) 
		{
			return true;
		}

		// Check if access type options are saved.
		if ( isset( $settings['subway-widget-access-type'] ) )
		{
			$access_type = $settings['subway-widget-access-type'];
				
			// Check to see if access type from options are valid.
			if ( in_array( $access_type, $this->allowed_access_type ) )
			{
				// Show the widget if the settings are set to public.
				if ( 'private' === $access_type )
				{
					
					// Do check for roles and subscription type here.
					$current_user_roles = Metabox::getUserRole( get_current_user_id() );
					$widget_roles_allowed = $settings['subway-widget-access-roles'];

					// Allow if the user has roles.
					if ( array_intersect( $current_user_roles, $widget_roles_allowed ) ) {
						return true;
					}
					
					echo wp_kses_post( $this->getNoAcessMessage( $settings, $args ) );

					return false;

				} 
				
				return true;
			}

			return true;
		}
		
		echo wp_kses_post( $this->getNoAcessMessage( $settings, $args ) );

		return false;

	}

	public function loadWidgetOptions( $widget, $return, $instance ) 
	{
		
		include SUBWAY_DIR_PATH . 'classes/services/templates/widget/widget-form.php';
		
	}

	public function saveWidgetOptions( $instance, $new_instance )
	{
		if ( ! isset( $new_instance['subway-widget-access-roles'] ) ) {
			$new_instance['subway-widget-access-roles'] = array();
		}
		return $new_instance;
	}

	public function getNoAcessMessage( $settings, $args )
	{
		?>
		<?php if ( isset( $settings['subway-widget-access-roles-message'] ) ): ?>
			<?php if ( ! empty ( $settings['subway-widget-access-roles-message'] ) ): ?>
				<?php echo $args['before_widget']; ?>
				<div class="widget-subway-no-access-message">
					<?php echo $settings['subway-widget-access-roles-message']; ?>
				</div>
				<?php echo $args['after_widget']; ?>
			<?php endif; ?>
		<?php endif; ?>
		<?php
	}
}

new WidgetService();
