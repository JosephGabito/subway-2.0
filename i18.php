<?php
/**
 * Plugin i18n.
 */
add_action( 'plugins_loaded', 'subway_localize_plugin' );

/**
 * Subway l10n callback.
 *
 * @return void
 */
function subway_localize_plugin() {

	$rel_path = SUBWAY_DIR_PATH . 'languages';

	load_plugin_textdomain( 'subway', false, $rel_path );

	return;
}

