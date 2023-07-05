<?php
/**
 * W83ShowCurrentWidth_Core
 *
 * @package Show_Current_Width
 */

namespace W83ShowCurrentWidth;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core class
 */
class W83ShowCurrentWidth_Core {

	// Singleton trait.
	use Singleton;

	/**
	 * Plugin constant.
	 */
	const PLUGIN_VERSION = '1.0.0';
	const PLUGIN_PREFIX  = 'w83-show-current-width';

	const OPTION_DEFAULT_BREAKPOINTS_DEFINITION = <<< EOT
		0,576,xs,X-Small
		576,768,sm,Small
		768,992,md,Medium
		992,1200,lg,Large
		1200,1400,xl,Extra large
		1400,9999,xll,Extra extra large
		EOT;
	const OPTION_DEFAULT_BREAKPOINTS_SHOW       = 1;
	const OPTION_DEFAULT_ADMIN_SHOW             = 0;
	const OPTION_DEFAULT_OTHER_INIT             = 0;

	/**
	 * Default values for table 'options'.
	 * Saved under the key 'w83-show-current-width_*'.
	 *
	 * @var array
	 */
	private $settings = array(
		'breakpoints_definition' => self::OPTION_DEFAULT_BREAKPOINTS_DEFINITION,
		'breakpoints_show'       => self::OPTION_DEFAULT_BREAKPOINTS_SHOW,
		'admin_show'             => self::OPTION_DEFAULT_ADMIN_SHOW,
		'other_init'             => self::OPTION_DEFAULT_OTHER_INIT,
	);

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	private function __construct() {
		// Initialize if option 'w83-show-current-width_other_init' is checked.
		if ( '1' === get_option( self::PLUGIN_PREFIX . '_other_init' ) ) {
			foreach ( $this->settings as $option_key => $option_value ) {
				delete_option( self::PLUGIN_PREFIX . '_' . $option_key );
			}
		}
		update_option( self::PLUGIN_PREFIX . '_other_init', 0 );

		// Load default value.
		foreach ( $this->settings as $option_key => $option_value ) {
			if ( false === get_option( self::PLUGIN_PREFIX . '_' . $option_key ) ) {
				update_option( self::PLUGIN_PREFIX . '_' . $option_key, $option_value );
				$this->settings[ $option_key ] = $option_value;
			}
		}

		// Load textdomain.
		add_action( 'admin_menu', array( $this, 'load_textdomain' ) );

		// Load CSS and JS.
		add_action( 'wp_enqueue_scripts', array( $this, 'load_css_js' ) );
		if ( get_option( self::PLUGIN_PREFIX . '_admin_show' ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'load_css_js' ) );
		}

		// Display width.
		add_action( 'admin_bar_menu', array( $this, 'display_width' ), 999 );
	}

	/**
	 * Load textdomain.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			self::PLUGIN_PREFIX,
			false,
			plugin_basename( dirname( dirname( __FILE__ ) ) ) . '/languages'
		);
	}

	/**
	 * Load CSS and JS.
	 *
	 * @return void
	 */
	public function load_css_js() {
		// CSS.
		wp_enqueue_style(
			self::PLUGIN_PREFIX . '-css',
			plugins_url( 'assets/show-current-width.min.css', dirname( __FILE__ ) ),
			array(),
			self::PLUGIN_VERSION
		);

		// JavaScript.
		wp_enqueue_script(
			self::PLUGIN_PREFIX . '-js',
			plugins_url( 'assets/show-current-width.min.js', dirname( __FILE__ ) ),
			array(),
			self::PLUGIN_VERSION,
			true
		);
		// Pass variables to JavaScript.
		$pass_to_js = array(
			'breakpoints_definition' => get_option( self::PLUGIN_PREFIX . '_breakpoints_definition' ),
			'breakpoints_show'       => get_option( self::PLUGIN_PREFIX . '_breakpoints_show' ),
		);
		wp_localize_script( self::PLUGIN_PREFIX . '-js', 'W83ShowCurrentWidth', $pass_to_js );
	}

	/**
	 * Display width.
	 *
	 * @param WP_Admin_Bar $wp_admin_bar WordPress admin bar.
	 * @return void
	 */
	public function display_width( $wp_admin_bar ) {
		if ( ! is_admin() || get_option( self::PLUGIN_PREFIX . '_admin_show' ) ) {
			if ( get_option( self::PLUGIN_PREFIX . '_breakpoints_show' ) ) {
				// Display breakpoint.
				$wp_admin_bar->add_node(
					array(
						'id'     => self::PLUGIN_PREFIX,
						'class'  => 'menupop',
						'title'  => '<span class="ab-icon" aria-hidden="true"><span class="width">????</span></span>' .
										'<span class="ab-label">' .
											'<span class="width-wrap"><span class="width">????</span>px</span>' .
											'<span class="breakpoint-wrap">(<span class="breakpoint">???</span>)</span>' .
										'</span>',
						'parent' => '',
						'href'   => '#',
					)
				);
				$wp_admin_bar->add_node(
					array(
						'id'     => self::PLUGIN_PREFIX . '-breakpoint',
						'title'  => '<span class="label">' . esc_html__( 'Breakpoint:', 'w83-show-current-width' ) . '</span>' .
										'<span class="breakpoint-wrap"><span class="breakpoint">???</span></span>',
						'parent' => self::PLUGIN_PREFIX,
						'href'   => '#',
					)
				);
			} else {
				// No display breakpoint.
				$wp_admin_bar->add_node(
					array(
						'id'     => self::PLUGIN_PREFIX,
						'class'  => 'menupop',
						'title'  => '<span class="ab-icon" aria-hidden="true"><span class="width">????</span></span>' .
										'<span class="ab-label">' .
											'<span class="width-wrap"><span class="width">????</span>px</span>' .
										'</span>',
						'parent' => '',
						'href'   => '#',
					)
				);
			}
		}
	}
}
