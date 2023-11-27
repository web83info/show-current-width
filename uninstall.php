<?php
/**
 * ShowCurrentWidth Uninstall
 *
 * @package Show_Current_Width
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

// Delete all options if option 'show-current-width_other_uninstall' is checked.
if ( is_multisite() ) {
	$sites = get_sites();
	foreach ( $sites as $site ) {
		switch_to_blog( $site->blog_id );
		delete_all_related_options();
		restore_current_blog();
	}
} else {
	delete_all_related_options();
}

/**
 * Delete all options core.
 */
function delete_all_related_options() {
	$alloptions = wp_load_alloptions( true );
	if ( '1' === get_option( 'show-current-width_other_uninstall' ) ) {
		foreach ( $alloptions as $option_key => $option_value ) {
			if ( false !== strpos( $option_key, 'show-current-width_' ) ) {
				delete_option( $option_key );
			}
		}
	}
}
