<?php
/**
 * W83ShowCurrentWidth_Core
 *
 * @package None
 */

namespace W83ShowCurrentWidth;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait Singleton {
	/**
	 * Instance.
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Constructor.
	 */
	protected function __construct() {
	}

	/**
	 * Get instance.
	 */
	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
