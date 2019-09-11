<?php
/**
 * This file is part of the Subway WordPress Plugin Package.
 * This file contains the class which handles the metabox of the plugin.
 *
 * (c) Joseph G <jasper@useissuestabinstead.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Props: Jasper Jardin
 *
 * PHP Version 5.4
 *
 * @category Subway\Metabox
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/subway
 * @link     github.com/codehaiku/subway The Plugin Repository
 */

namespace Subway;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Subway Metabox methods.
 *
 * @category Subway\Metabox
 * @package  Subway
 * @author   Jasper J. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/subway The Plugin Repository
 * @since    2.0.9
 */
final class Metabox {


	/**
	 * Subway visibility meta value,
	 *
	 * @since 2.0.9
	 * @const string VISIBILITY_METAKEY
	 */
	const VISIBILITY_METAKEY = 'subway_visibility_meta_key';

	/**
	 * Registers and update metabox with its intended method below.
	 *
	 * @since  2.0.9
	 * @return void
	 */
	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'addMetabox' ) );
		add_action( 'save_post', array( $this, 'saveMetaboxValues' ) );

		return $this;
	}

	/**
	 * Initialize metabox
	 *
	 * @since  2.0.9
	 * @access public
	 * @return void
	 */
	public static function initMetabox() {

		new Metabox();
	}

	/**
	 * Initialize metabox
	 *
	 * @since  2.0.9
	 * @access public
	 * @return void
	 */
	public function addMetabox() {

		$post_types = $this->getPostTypes();

		foreach ( $post_types as $post_type => $value ) {
			add_meta_box(
				'subway_visibility_metabox',
				esc_html__( 'Subway: Visibility Option', 'subway' ),
				array( $this, 'visibilityMetabox' ),
				$post_type,
				'side',
				'high'
			);
		}
	}

	/**
	 * This method displays the Subway Visibility checkbox.
	 *
	 * @param object $post Contains data from the current post.
	 *
	 * @since  2.0.9
	 * @access public
	 * @return void
	 */
	public function visibilityMetabox( $post ) {

		$howto = __(
			'Choose the accessibility of this page from the options above.',
			'subway'
		);

		$private_setting_label = __( 'Members Only', 'subway' );

		$is_post_private         = self::isPostPrivate( $post->ID );

		// Make sure the form request comes from WordPress
		wp_nonce_field( basename( __FILE__ ),  'subway_post_visibility_nonce' );

		// Disable the options (radio) when site is selected as public
		?>
		<input type="hidden" name="subway-visibility-form-submitted" value="1" />

		<?php if ( ! Options::isPublicSite() ) :  ?>
		<?php // Site is private. Give them some Beer! ?>
			<p>
				<label class="subway-visibility-settings-checkbox-label" for="subway-visibility-public">
					<input type="radio" class="subway-visibility-settings-radio" id="subway-visibility-public" name="subway-visibility-settings" value="public" <?php echo checked( false, $is_post_private, false ); ?>>
					<?php esc_html_e( 'Public', 'subway' ) ?>
				</label>
			</p>
			<p>
				<label class="subway-visibility-settings-checkbox-label" for="subway-visibility-private">
					<input type="radio" class="subway-visibility-settings-radio" id="subway-visibility-private" name="subway-visibility-settings"
					value="private" <?php echo checked( true, $is_post_private, false ); ?>>
					<?php esc_html_e( 'Members Only', 'subway' ) ?>
				 </label>
			</p>
			<div id="subway-roles-access-visibility-fields" class="hidden">
				<dl>
					<?php $post_allowed_user_roles = self::getAllowedUserRoles( $post->ID ); ?>
					<?php $editable_roles = get_editable_roles(); ?>
					<?php // Remove administrator for editable roles. ?>
					<?php unset( $editable_roles['administrator'] ); ?>
					<?php foreach ( $editable_roles as $role_name => $role_info ) { ?>
						<dt>
							<?php $id = 'subway-visibility-settings-user-role-' . esc_html( $role_name ); ?>
							<label for="<?php echo esc_attr( $id ); ?>">
							<?php if ( is_array( $post_allowed_user_roles ) && in_array( $role_name, $post_allowed_user_roles ) ) { ?>
								<?php $checked = 'checked'; ?>
							<?php } else { ?>
								<?php if ( false === $post_allowed_user_roles ) { ?>
									<?php $checked = 'checked'; ?>
								<?php } else { ?>
										<?php $checked = ''; ?>
								<?php } ?>
							<?php } ?>
							<input <?php echo esc_attr( $checked ); ?> id="<?php echo esc_attr( $id ); ?>" type="checkbox" 
							name="subway-visibility-settings-user-role[]" class="subway-visibility-settings-role-access" value="<?php echo esc_attr( $role_name ); ?>" />
								<?php echo esc_html( $role_info['name'] ); ?>
							</label>
						</dt>
					<?php } ?>
					<p class="howto"><?php echo esc_html_e( 'Uncheck the user roles that you do not want to have access to this content','subway' ); ?></p>
					<p>
						<dl>
							<dt>
								<strong>
									<?php esc_html_e('No Access Control', 'subway'); ?>
								</strong>
							</dt>
						</dl>
						<dl>
							<label>
								<input type="radio" name="subway-visibility-settings-no-access-type" />
								<?php esc_html_e('Redirect (302) to', 'subway'); ?> 
								<a target="_blank" href="<?php echo esc_url( Options::getRedirectPageUrl() ); ?>" title="<?php esc_attr_e('Login Page', 'subway'); ?>">
									<?php esc_html_e('Login Page', 'subway'); ?>
								</a>
							</label>
						</dl>
						<dl>
							<label>
								<input type="radio" name="subway-visibility-settings-no-access-type" />
								<?php esc_html_e('Block Post Content', 'subway'); ?>
							</label>
						</dl>
					</p>
					<p class="howto">
						<?php esc_html_e('Choose what type of behaviour would you like to have if the user has no access to the content.', 'subway'); ?>
					</p>
				</dl>
			</div>
			<script>
				jQuery(document).ready(function($){
					'use strict';
					if ( $('#subway-visibility-private').is(':checked') ) {
						$('#subway-roles-access-visibility-fields').css('display', 'block');
					}
					$('.subway-visibility-settings-radio').click(function(){
						$('#subway-roles-access-visibility-fields').css('display', 'none');
						if ( $('#subway-visibility-private').is(':checked') ) {
							$('#subway-roles-access-visibility-fields').css('display', 'block');
						}
					});
				});
			</script>
			<p class="howto"><?php echo esc_html( $howto ); ?></p>
		<?php else : ?>
			<?php // Site is public! Explain to them ?>
			<p><em>
				<?php esc_html_e( 'You have chosen to make your site public inside Settings > Subway.  Subway visibility options will be turned off.', 'subway' ); ?>
			</em>
			</p>
		<?php endif; ?>
		<?php
	}

	/**
	 * This method verify if nonce is valid then updates a post_meta.
	 *
	 * @param integer $post_id Contains ID of the current post.
	 *
	 * @since  2.0.9
	 * @access public
	 * @return boolean false Returns false if nonce is not valid.
	 */
	public function saveVisibilityMetabox( $post_id = '' ) {

		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		$is_form_submitted = filter_input( INPUT_POST, 'subway-visibility-form-submitted', FILTER_DEFAULT );

		if ( ! $is_form_submitted ) {
			return;
		}

		$public_posts     = Options::getPublicPostsIdentifiers();

		$posts_implode    = '';

		$visibility_field = 'subway-visibility-settings';

		$visibility_nonce = filter_input(
			INPUT_POST, 'subway_post_visibility_nonce',
			FILTER_SANITIZE_STRING
		);

		$post_visibility = filter_input( INPUT_POST,  $visibility_field, FILTER_SANITIZE_STRING );

		$is_valid_visibility_nonce = self::isNonceValid( $visibility_nonce );

		$allowed_roles = filter_input( INPUT_POST, 'subway-visibility-settings-user-role', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		// verify taxonomies meta box nonce
		if ( false === $is_valid_visibility_nonce ) {
			return;
		}
		if ( empty( $allowed_roles ) ) {
			$allowed_roles = array();
		}

		// Update user roles.
		update_post_meta( $post_id, 'subway-visibility-settings-allowed-user-roles', $allowed_roles );

		if ( ! empty( $post_visibility ) ) {
			if ( ! empty( $post_id ) ) {
				if ( 'public' === $post_visibility ) {
					if ( ! in_array( $post_id, $public_posts ) ) {
						array_push( $public_posts, $post_id );
					}
				}
				if ( 'private' === $post_visibility ) {
					if ( in_array( $post_id, $public_posts ) ) {
						unset( $public_posts[ array_search( $post_id, $public_posts ) ] );
					}
				}
			}
		}

		if ( ! empty( $post_id ) ) {
			$posts_implode = implode( ', ', $public_posts );

			if ( 'inherit' !== get_post_status( $post_id ) ) {

				if ( true === $is_valid_visibility_nonce ) {
					update_option( 'subway_public_post', $posts_implode );
					update_post_meta(
						$post_id,
						self::VISIBILITY_METAKEY,
						$post_visibility
					);
				}
			}
		}
	}

	/**
	 * This method runs the methods that handles the update for a post_meta.
	 *
	 * @param integer $post_id Contains ID of the current post.
	 *
	 * @since  2.0.9
	 * @access public
	 * @return boolean false Returns false if nonce is not valid.
	 */
	public function saveMetaboxValues( $post_id ) {

		$this->saveVisibilityMetabox( $post_id );
		return;
	}

	/**
	 * Initialize metabox arguments.
	 *
	 * @param array  $args   The arguments for the get_post_types().
	 * @param string $output Your desired output for the data.
	 *
	 * @since  2.0.9
	 * @access public
	 * @return void
	 */
	public static function getPostTypes( $args = '', $output = '' ) {

		if ( empty( $args ) ) {
			$args = array(
			'public'   => true,
			);
			$output = 'names';
		}

		$post_types = get_post_types( $args, $output );

		return $post_types;
	}

	/**
	 * This method verify if nonce is valid.
	 *
	 * @param mixed $nonce the name of a metabox nonce.
	 *
	 * @since  2.0.9
	 * @access public
	 * @return boolean true Returns true if nonce is valid.
	 */
	public function isNonceValid( $nonce ) {

		if ( ! isset( $nonce ) || ! wp_verify_nonce( $nonce, basename( __FILE__ ) ) ) {
			return;
		}

		return true;
	}

	/**
	 * Checks if a post is set to private.
	 *
	 * @param integer $post_id Contains ID of the current post.
	 *
	 * @since  2.0.9
	 * @access public
	 * @return boolean true Returns true if post is private. Otherwise false.
	 */
	public static function isPostPrivate( $post_id ) {

		$meta_value = '';

		if ( ! empty( $post_id ) ) 
		{
			$meta_value = get_post_meta( $post_id, self::VISIBILITY_METAKEY, true );
			// Pages that dont have meta values yet. 
			if ( empty( $meta_value ) ) 
			{
				// Give it a public visibility.
				return false;
			}
			if ( 'private' === $meta_value ) 
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Get the allowed users roles
	 *
	 * @param  integer $post_id The post ID.
	 * @return mixed Boolean false if metadata does not exists. Otherwise, return the array value of meta.
	 */
	public static function getAllowedUserRoles( $post_id = 0 ) {

		$allowed_roles = array();

		if ( ! empty( $post_id ) ) {

			// Check if metadata exists for the following post.
			if ( metadata_exists( 'post', $post_id, 'subway-visibility-settings-allowed-user-roles' ) ) {

				$allowed_roles = get_post_meta( $post_id, 'subway-visibility-settings-allowed-user-roles', true );
				if ( ! is_null( $allowed_roles ) ) {
					return $allowed_roles;
				}
				return false;
				
			} else {
				return false;
			}

		} else {
			return false;
		}

		return $allowed_roles;
	}

	/**
	 * Gets the role of the user.
	 *
	 * @param  integer $user id The user id.
	 * @return array The user roles.
	 */
	public function getUserRole( $user_id = 0 ) 
	{

		$roles = array();
		
		$user = get_userdata( absint( $user_id ) );
		
		if ( ! empty( $user->roles ) ) {
			$roles = $user->roles;
		}

		return $roles;
	}

	/**
	 * Gets the subscription type of the specific post type.
	 *
	 * @param  integer $post_id The post id.
	 * @return array The subscription type.
	 */
	public function getSubscriptionType( $post_id = 0 ) {

		$user_roles = get_post_meta( $post_id, 'subway-visibility-settings-allowed-user-roles', true );

		$visibility = get_post_meta( $post_id, 'subway_visibility_meta_key', true);

		if ( empty( $visibility ) ) { $visibility = 'public'; }

		if ( empty( $user_roles ) ) { $user_roles = array(); }

		return array( 
				'type' => $visibility, 
				'roles' => $user_roles, 
				'subscription_type' => array() 
			);

	}

	public function isCurrentUserSubscribedTo($post_id) 
	{
		// Yes, for admin.
		if ( current_user_can('manage_plugins') )
		{
			return true;
		}
	
		// Check the subscribe type of the current post type.
		$post_subscribe_type = Metabox::getSubscriptionType( $post_id );
		
		if ( 'private' === $post_subscribe_type['type'] )
		{
			$user_role = Metabox::getUserRole( get_current_user_id() );

			// If the user role matches checked subscription role.
			if ( empty( array_intersect( $user_role, $post_subscribe_type['roles'] ) ) ) {
				return false;
			}
		}

		return true;
	}

	public function isPostTypeRedirect() {
		return true;
	}

}
