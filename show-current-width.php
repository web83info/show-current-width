<?php
/**
 * ShowCurrentWidth
 *
 * @package Show_Current_Width
 */

namespace ShowCurrentWidth;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'class/trait-singleton.php';
require_once 'class/class-showcurrentwidth-core.php';
require_once 'class/class-showcurrentwidth-admin.php';

$show_current_width_core  = ShowCurrentWidth_Core::get_instance();
$show_current_width_admin = ShowCurrentWidth_Admin::get_instance();
