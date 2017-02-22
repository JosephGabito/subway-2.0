<?php
/**
 * Callback function for 'subway_is_public' setting
 *
 * @return void
 */
function subway_is_public_form() {

	echo '<label for="subway_is_public"><input ' . checked( 1, get_option( 'subway_is_public' ), false ) . ' value="1" name="subway_is_public" id="subway_is_public" type="checkbox" class="code" /> Check to make all of your posts and pages visible to public.</label>';
	echo '<p class="description">' . esc_html__( 'This option will overwrite the \'Private Login Page\' below. BuddyPress pages like user profile, members, and groups are still only available to the rightful owner of the profile.', 'subway' ) . '</p>';

	return;
}
?>