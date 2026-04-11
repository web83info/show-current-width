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

ShowCurrentWidth_Core::get_instance();
ShowCurrentWidth_Admin::get_instance();
