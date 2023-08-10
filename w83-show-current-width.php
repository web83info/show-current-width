<?php
/**
 * Plugin Name:         Show Current Width
 * Plugin URI:
 * Description:         This plugin shows a current screen width on WP adminbar.
 * Version:             1.1.2
 * Requires at least:   6.0
 * Requires PHP:        7.4
 * Author:              web83info <me@web83.info>
 * Author URI:
 * Requires License:    no
 * License:             GPLv2+
 * Text Domain:         w83-show-current-width
 * Domain Path:         /languages
 *
 * @package Show_Current_Width
 * @author  web83info
 * @link
 * @license
 */

namespace W83ShowCurrentWidth;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'class/trait-singleton.php';
require_once 'class/class-w83showcurrentwidth-core.php';
require_once 'class/class-w83showcurrentwidth-admin.php';

$w83_show_current_width_core  = W83ShowCurrentWidth_Core::get_instance();
$w83_show_current_width_admin = W83ShowCurrentWidth_Admin::get_instance();
