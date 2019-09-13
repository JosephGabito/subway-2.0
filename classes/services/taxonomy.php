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
 * @category Subway\TaxonomyService
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
final class TaxonomyService {
	
	public function __construct()
	{
		$taxonomy = 'category';
		
		add_action( $taxonomy . '_edit_form_fields', array($this, 'taxonomyOption'), 10123, 2 );

		// Save the changes made on the "presenters" taxonomy, using our callback function  
		add_action( 'edited_'. $taxonomy, array($this, 'saveTaxonomyOption'), 10, 2 ); 

		add_action( 'wp', array( $this, 'authorizeTaxonomyTerm' ) );

		return $this;
	}

	public function taxonomyOption( $term ) {
		?>
		<tr class="form-field subway-membership-access-type">  
		    <th scope="row" valign="top">  
		        <label for="subway-membership-access-type">
		        	<?php esc_html_e('Membership Access', 'subway'); ?>
		        </label>  
		    </th>  
		    
		    <td>
		    	<?php
		    	$access_type = get_term_meta($term->term_id, 'subway_membership_access_type', true ); 
		    	if ( empty ( $access_type ) ) {
		    		$access_type = 'public';
		    	}
		    	?>
		    	<!--Membership Access Type-->
		    	<p style="margin-top: 0px;">
		    	<label>  
		    		<input <?php checked($access_type,'public', true); ?> type="radio" name="subway_term_meta[subway_membership_access_type]"
		    			class="subway_membership_access_type_radio"
		    			value="public" id="subway_membership_access_type_public" />
		    		<?php esc_html_e('Public','subway'); ?>
		       	</label>
		       	</p>
		       	<p class="howto">
		       		<?php esc_html_e("Select the 'Public' option to make this term accessible to all users and readers.",'subway'); ?>
		       	</p>
		       	<p>
		       	<?php $display = 'none'; ?>
		       	<?php if ( 'private' === $access_type ): ?>
		       		<?php $display = 'block'; ?>
		       	<?php endif; ?>
		       	<label>  
		    		<input <?php checked($access_type,'private', true); ?> type="radio" name="subway_term_meta[subway_membership_access_type]"
		    			class="subway_membership_access_type_radio"
		    			value="private" id="subway_membership_access_type_private" />
		    		<?php esc_html_e('Members Only','subway'); ?>
		       	</label>
		       </p>
		      
		        <p class="howto">
		        	<?php esc_html_e( "Select the ‘Private’ option to redirect the users to the login page when a user visit the archive of this taxonomy term. ", 'subway'); ?>
		        </p>  
		        <!--Membership Access Type End-->
		       
		        <dl id="subway-term-membership-role-access-wrap" style="display: <?php echo esc_attr( $display); ?>">

		        	<?php $editable_roles = get_editable_roles(); ?>
		        	<?php $selected_roles = get_term_meta( $term->term_id, 'subway_membership_access_type_roles', true ); ?>
		        	
		        	<?php // Set the default to 'check all'?>
		        	<?php unset( $editable_roles['administrator'] ); ?>
					<?php foreach ( $editable_roles as $role_name => $role_info ): ?>
						<?php $checked = ''; ?>
						<?php if ( in_array( $role_name, (array)$selected_roles ) ): ?>
							<?php $checked = 'checked'; ?>
						<?php endif; ?>
						<?php if ( false === $selected_roles ): ?>
			        		<?php $checked = 'checked'; ?>
			        	<?php endif; ?>
						<dt>
			        		<label>
			        			<input <?php echo esc_attr($checked); ?> value="<?php echo esc_attr($role_name); ?>" name="subway_term_meta[subway_membership_access_type_role][]" type="checkbox" />
			        			<?php echo esc_html( $role_info['name'] ); ?>
			        		</label>
			        	</dt>
					<?php endforeach; ?>
					<dt>
						<p class="howto">
							<?php esc_html_e('Uncheck the user roles that you do not want to have access to this content', 'subway'); ?>
						</p>
					</dt>
		        </dl>
		    </td>
		</tr>  
		<script>
			jQuery(document).ready(function($){
				'use strict';
				var display = 'none';
				$('.subway_membership_access_type_radio').change( function(){
					if ( 'private' === $(this).val() ) {
						display = "block";
					} else {
						display = "none";
					}
					$('#subway-term-membership-role-access-wrap').css('display', display);
				});
			});
		</script>
	<?php
	}

	// A callback function to save our extra taxonomy field(s)  
	public function saveTaxonomyOption( $term_id ) 
	{  
		// Get requested term meta.
		$subway_term_meta = filter_input( INPUT_POST, 'subway_term_meta', FILTER_DEFAULT, FILTER_FORCE_ARRAY );
		// Make the default value public.
		$subway_membership_access_type = 'public';
		// Filter allowed access type.
		$allowed_subway_membership_access_type = array('private', 'public');
		
		// Bail out if not array.
		if ( ! is_array( $subway_term_meta ) )
		{
			return;
		}
		// Bail out if empty.
		if ( empty ( $subway_term_meta ) )
		{
			return;
		}
		// Assign requested access type.
		$subway_membership_access_type = $subway_term_meta['subway_membership_access_type'];
		$subway_membership_access_type_roles = (array)$subway_term_meta['subway_membership_access_type_role'];
		
		// Check if membership access type is in allowed access type.
		if ( ! in_array( $subway_membership_access_type, $allowed_subway_membership_access_type) )
		{
			return;
		}
		// Once everything is fine, save it to term meta.
		update_term_meta( $term_id, 'subway_membership_access_type', $subway_membership_access_type ); 
		update_term_meta( $term_id, 'subway_membership_access_type_roles', $subway_membership_access_type_roles ); 
	   	
	}

	public function authorizeTaxonomyTerm()
	{
		// Bail out if admin is viewing the page.
		if ( current_user_can('manage_options') )
		{
			return;
		}
		if ( is_tax() || is_category() || is_tag() )
		{
			$term_id = get_queried_object()->term_id;

			if ( empty ( $term_id ) ) {
				return;
			}

			$access_type = get_term_meta( $term_id, 'subway_membership_access_type', true );
			$allowed_user_roles = get_term_meta( $term_id, 'subway_membership_access_type_roles', true );
			$current_user_role = \Subway\Metabox::getUserRole( get_current_user_id() );
			
			if ( 'private' === $access_type )
			{
				// If no user role is found.
				if ( ! array_intersect( $allowed_user_roles, $current_user_role ) ) {
					$login_page_url = Options::getRedirectPageUrl();
					wp_safe_redirect( $login_page_url, 302 );
					exit;
				}
			}
		}	
	}

}

new TaxonomyService();