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
if ( is_multisite() ) {
	global $wpdb;
	$blogid_current = get_current_blog_id();
	$blogids        = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
	foreach ( $blogids as $blogid ) {
		switch_to_blog( $blogid );
		if ( '1' === get_option( 'w83-show-current-width_other_uninstall' ) ) {
			// Delete all options whose key begins with 'w83-show-current-width_'.
			delete_all_options( wp_load_alloptions( true ) );
		}
	}
	switch_to_blog( $blogid_current );
} else {
	if ( '1' === get_option( 'w83-show-current-width_other_uninstall' ) ) {
		// Delete all options whose key begins with 'w83-show-current-width_'.
		delete_all_options( wp_load_alloptions( true ) );
	}
}

/**
 * Delete all options core.
 *
 * @param array $alloptions Load all options.
 */
function delete_all_options( $alloptions ) {
	foreach ( $alloptions as $option_key => $option_value ) {
		if ( false !== strpos( $option_key, 'w83-show-current-width_' ) ) {
			delete_option( $option_key );
		}
	}
}
