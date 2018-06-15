<?php
/**
 * This file is part of the Subway WordPress Plugin Package.
 * This file contains the class which handles the metabox of the plugin.
 *
 * (c) Joseph Gabito <joseph@useissuestabinstead.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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

if (! defined('ABSPATH') ) {
    return;
}

/**
 * Subway Metabox methods.
 *
 * @category Subway\Metabox
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/subway The Plugin Repository
 * @since    2.0.9
 */
final class Metabox
{

	/**
	 * Subway visibility meta value,
	 *
	 * @since  2.0.9
	 * @const string VISIBILITY_METAKEY
	 */
	const VISIBILITY_METAKEY = 'subway_visibility_meta_key';

	/**
	 * Registers and update metabox with its intended method below.
	 *
	 * @since  2.0.9
	 * @return void
	 */
	public function __construct()
	{
		add_action(
			'add_meta_boxes',
			array(
				$this,
				'addMetabox'
			)
		);
		add_action(
			'save_post',
			array(
				$this,
				'saveMetaboxValues'
			)
		);
	}

	/**
	 * Initialize metabox
	 *
	 * @since  2.0.9
	 * @access public
	 * @return void
	 */
	public static function initMetabox()
	{
		new Metabox();
	}

	/**
	 * Initialize metabox
	 *
	 * @since  2.0.9
	 * @access public
	 * @return void
	 */
	public function addMetabox()
	{
		$post_types = $this->getPostTypes();

		foreach ( $post_types as $post_type => $value ) {
			add_meta_box(
				'subway_visibility_metabox',
				esc_html__( 'Subway Post Visibility Setting', 'subway' ),
				array( $this, 'visibilityMetabox' ),
				$post_type,
				'side',
				'low'
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
    public function visibilityMetabox( $post )
    {
		$howto         = __( 'Tick this checkbox to make it private. Otherwise, uncheck to make it public.', 'subway' );
		$setting_label = __( 'Check to make post visible.', 'subway' );

        // Make sure the form request comes from WordPress
        wp_nonce_field(
            basename(__FILE__),
            'subway_post_visibility_nonce'
        );

        $value = get_post_meta( $post->ID, self::VISIBILITY_METAKEY, true);
        ?>
        <label class="screen-reader-text" for="subway-visibility"><?php echo esc_html( $setting_label ); ?></label>

		<label class="subway-visibility-settings-checkbox-label" for="subway-visibility-settings-checkbox">
			<input type="checkbox" class="subway-visibility-settings-checkbox" id="subway-visibility-settings-checkbox" name="subway-visibility-settings-checkbox" value="1" <?php echo checked( 1, esc_attr( $value ), false ); ?>>
			<?php echo esc_html( $setting_label ); ?>
		</label>

        <p class="howto"><?php echo esc_html( $howto ); ?></p>

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
    public function saveMetaboxValues( $post_id )
    {
	}

	/**
	 * Initialize metabox
	 *
	 * @since  2.0.9
	 * @access public
	 * @return void
	 */
	public static function getPostTypes( $args = '', $output = '' )
	{
		if ( empty( $args ) ) {
			$args = array(
				'public'   => true,
			);
		}

		if ( empty( $args ) ) {
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
    public function isNonceValid( $nonce )
    {
        if ( !isset( $nonce ) || !wp_verify_nonce( $nonce, basename( __FILE__ ) ) ) {
            return;
        }

        return true;
    }
}
