<?php
/**
 * Show Current Width
 *
 * @package             Show_Current_Width
 * @author              web83info
 * @copyright           2023 web83info
 * @license             GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:         Show Current Width
 * Plugin URI:
 * Description:         This plugin shows a current screen width on WP adminbar.
 * Version:             1.2.7
 * Requires at least:   6.0
 * Tested up to:        6.5.3
 * Requires PHP:        7.4
 * Author:              web83info <me@web83.info>
 * Author URI:
 * License:             GPL v2 or later
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
 */

namespace ShowCurrentWidth;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'show-current-width.php';
