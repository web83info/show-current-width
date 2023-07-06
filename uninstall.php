<?php
/**
 * W83ShowCurrentWidth Uninstall
 *
 * @package Show_Current_Width
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

// Delete all options if option 'w83-show-current-width_other_uninstall' is checked.
if ( '1' === get_option( 'w83-show-current-width_other_uninstall' ) ) {
	// Delete all options whose key begins with 'w83-show-current-width_'.
	foreach ( wp_load_alloptions( true ) as $option_key => $option_value ) {
		if ( false !== strpos( $option_key, 'w83-show-current-width_' ) ) {
			delete_option( $option_key );
		}
	}
}
