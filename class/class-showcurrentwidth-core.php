<?php
/**
 * ShowCurrentWidth_Core
 *
 * @package Show_Current_Width
 */

namespace ShowCurrentWidth;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core class
 */
class ShowCurrentWidth_Core {

	// Singleton trait.
	use Singleton;

	/**
	 * Plugin constant.
	 */
	const PLUGIN_VERSION           = '1.2.7';
	const PLUGIN_PREFIX            = 'show-current-width';
	const PLUGIN_PREFIX_DEPRECATED = 'w83-show-current-width';
	const PLUGIN_GITHUB            = 'https://github.com/web83info/show-current-width';

	const OPTION_DEFAULT_BREAKPOINTS_DEFINITION     =
		'0,576,xs,X-Small' . PHP_EOL .
		'576,768,sm,Small' . PHP_EOL .
		'768,992,md,Medium' . PHP_EOL .
		'992,1200,lg,Large' . PHP_EOL .
		'1200,1400,xl,Extra large' . PHP_EOL .
		'1400,9999,xll,Extra extra large';
	const OPTION_DEFAULT_BREAKPOINTS_SHOW           = 1;
	const OPTION_DEFAULT_BREAKPOINTS_LIMITWIDTH     = 0;
	const OPTION_DEFAULT_BREAKPOINTS_LIMITWIDTH_MIN = 0;
	const OPTION_DEFAULT_BREAKPOINTS_LIMITWIDTH_MAX = 9999;
	const OPTION_DEFAULT_ANIMATION_SHOW             = 1;
	const OPTION_DEFAULT_ANIMATION_TIMEOUT          = 500;
	const OPTION_DEFAULT_ADMIN_SHOW                 = 0;
	const OPTION_DEFAULT_CONDITION_ROLE             = array( 'administrator' );
	const OPTION_DEFAULT_OTHER_INIT                 = 0;
	const OPTION_DEFAULT_OTHER_UNINSTALL            = 0;

	/**
	 * Default values for table 'options'.
	 * Saved under the key 'show-current-width_*'.
	 *
	 * @var array
	 */
	private $settings = array(
		'breakpoints_definition'     => self::OPTION_DEFAULT_BREAKPOINTS_DEFINITION,
		'breakpoints_show'           => self::OPTION_DEFAULT_BREAKPOINTS_SHOW,
		'breakpoints_limitwidth'     => self::OPTION_DEFAULT_BREAKPOINTS_LIMITWIDTH,
		'breakpoints_limitwidth_min' => self::OPTION_DEFAULT_BREAKPOINTS_LIMITWIDTH_MIN,
		'breakpoints_limitwidth_max' => self::OPTION_DEFAULT_BREAKPOINTS_LIMITWIDTH_MAX,
		'animation_show'             => self::OPTION_DEFAULT_ANIMATION_SHOW,
		'animation_timeout'          => self::OPTION_DEFAULT_ANIMATION_TIMEOUT,
		'admin_show'                 => self::OPTION_DEFAULT_ADMIN_SHOW,
		'condition_role'             => self::OPTION_DEFAULT_CONDITION_ROLE,
		'other_init'                 => self::OPTION_DEFAULT_OTHER_INIT,
		'other_uninstall'            => self::OPTION_DEFAULT_OTHER_UNINSTALL,
	);

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	private function __construct() {
		// Update deprecated options (Remove prefix w83-).
		foreach ( $this->settings as $option_key => $option_value ) {
			$option = get_option( self::PLUGIN_PREFIX_DEPRECATED . '_' . $option_key );
			if ( false !== $option ) {
				update_option( self::PLUGIN_PREFIX . '_' . $option_key, $option );
				delete_option( self::PLUGIN_PREFIX_DEPRECATED . '_' . $option_key );
			}
		}

		// Initialize if option 'show-current-width_other_init' is checked.
		if ( '1' === get_option( self::PLUGIN_PREFIX . '_other_init' ) ) {
			foreach ( $this->settings as $option_key => $option_value ) {
				delete_option( self::PLUGIN_PREFIX . '_' . $option_key );
			}
		}

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
		load_plugin_textdomain( self::PLUGIN_PREFIX );
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
			plugins_url( 'assets/show-current-width.min.css', __DIR__ ),
			array(),
			self::PLUGIN_VERSION
		);

		// Load CSS inline.
		$limitwidth_min = get_option( self::PLUGIN_PREFIX . '_breakpoints_limitwidth_min' );
		$limitwidth_max = get_option( self::PLUGIN_PREFIX . '_breakpoints_limitwidth_max' );
		if ( '1' === get_option( self::PLUGIN_PREFIX . '_breakpoints_limitwidth' ) ) {
			wp_register_style(
				self::PLUGIN_PREFIX . '-css-inline',
				false,
				array(),
				self::PLUGIN_VERSION
			);
			wp_enqueue_style(
				self::PLUGIN_PREFIX . '-css-inline',
			);
			$css = <<< EOT
			@media (max-width: {$limitwidth_min}px),  (min-width: {$limitwidth_max}px) {
				#wp-admin-bar-show-current-width {
					display: none !important;
				}
			}
			EOT;
			wp_add_inline_style(
				self::PLUGIN_PREFIX . '-css-inline',
				$css
			);
		}

		// JavaScript.
		wp_enqueue_script(
			self::PLUGIN_PREFIX . '-js',
			plugins_url( 'assets/show-current-width.min.js', __DIR__ ),
			array(),
			self::PLUGIN_VERSION,
			array(
				'strategy' => 'defer',
			)
		);

		// Pass variables to JavaScript.
		$pass_to_js = array(
			'breakpoints_definition' => get_option( self::PLUGIN_PREFIX . '_breakpoints_definition' ),
			'breakpoints_show'       => get_option( self::PLUGIN_PREFIX . '_breakpoints_show' ),
			'animation_show'         => get_option( self::PLUGIN_PREFIX . '_animation_show' ),
			'animation_timeout'      => get_option( self::PLUGIN_PREFIX . '_animation_timeout' ),
		);
		wp_localize_script( self::PLUGIN_PREFIX . '-js', 'ShowCurrentWidthVariables', $pass_to_js );
	}

	/**
	 * Display width.
	 *
	 * @param WP_Admin_Bar $wp_admin_bar WordPress admin bar.
	 * @return void
	 */
	public function display_width( $wp_admin_bar ) {
		// Return if not allowed in admin page.
		if ( is_admin() && ! get_option( self::PLUGIN_PREFIX . '_admin_show' ) ) {
			return;
		}

		// Return if not having an appropriate user role.
		$roles = get_option( self::PLUGIN_PREFIX . '_condition_role' );
		if ( 0 === count( array_intersect( wp_get_current_user()->roles, $roles ) ) ) {
			return;
		}

		if ( get_option( self::PLUGIN_PREFIX . '_breakpoints_show' ) ) {
			// Display breakpoint.
			$breakpoints_definition = str_replace(
				array( "\r\n", "\r", "\n" ),
				"\n",
				get_option( self::PLUGIN_PREFIX . '_breakpoints_definition' )
			);
			$breakpoints            = explode( "\n", $breakpoints_definition );
			foreach ( $breakpoints as $breakpoint_key => $breakpoint_value ) {
				$breakpoints[ $breakpoint_key ] = explode( ',', $breakpoint_value );
				if ( count( $breakpoints[ $breakpoint_key ] ) < 4 ) {
					unset( $breakpoints[ $breakpoint_key ] );
				}
			}

			$wp_admin_bar->add_node(
				array(
					'id'     => self::PLUGIN_PREFIX,
					'class'  => 'menupop',
					'title'  => '<span class="ab-icon" aria-hidden="true"><span class="width">0</span></span>' .
									'<span class="ab-label">' .
										'<span class="width-wrap"><span class="width">0</span>px</span>' .
										'<span class="breakpoint-wrap">(<span class="breakpoint"></span>)</span>' .
									'</span>',
					'parent' => '',
					'href'   => '#',
				)
			);
			$wp_admin_bar->add_node(
				array(
					'id'     => self::PLUGIN_PREFIX . '-breakpoint',
					'title'  => '<span class="label">' . esc_html__( 'Breakpoint:', 'show-current-width' ) . '</span>' .
									'<span class="breakpoint-wrap"><span class="breakpoint"></span></span>',
					'parent' => self::PLUGIN_PREFIX,
					'href'   => '#',
				)
			);
			$breakpoint_index = 0;
			foreach ( $breakpoints as $breakpoint ) {
				$wp_admin_bar->add_node(
					array(
						'id'     => self::PLUGIN_PREFIX . '-breakpoint-' . $breakpoint_index,
						'title'  =>
							sprintf(
								'<span class="icon"></span>' .
								'<span class="name">%s:</span>' .
								'<span class="range">%s &le; %s < %s</span>',
								$breakpoint[3],
								$breakpoint[0],
								$breakpoint[2],
								$breakpoint[1]
							),
						'parent' => self::PLUGIN_PREFIX,
						'href'   => '#',
					)
				);
				++$breakpoint_index;
			}
		} else {
			// No display breakpoint.
			$wp_admin_bar->add_node(
				array(
					'id'     => self::PLUGIN_PREFIX,
					'class'  => 'menupop',
					'title'  => '<span class="ab-icon" aria-hidden="true"><span class="width">0</span></span>' .
									'<span class="ab-label">' .
										'<span class="width-wrap"><span class="width">0</span>px</span>' .
									'</span>',
					'parent' => '',
					'href'   => '#',
				)
			);
		}
		$wp_admin_bar->add_node(
			array(
				'id'     => self::PLUGIN_PREFIX . '-link-setting',
				'class'  => 'menupop',
				'title'  => __( 'Open settings page', 'show-current-width' ),
				'parent' => self::PLUGIN_PREFIX,
				'href'   => admin_url( 'options-general.php?page=' . self::PLUGIN_PREFIX ),
			)
		);
	}
}
