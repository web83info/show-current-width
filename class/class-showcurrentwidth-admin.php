<?php
/**
 * ShowCurrentWidth_Admin
 *
 * @package Show_Current_Width
 */

namespace ShowCurrentWidth;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin class
 */
class ShowCurrentWidth_Admin {

	// Singleton trait.
	use Singleton;

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	private function __construct() {
		// Plugin setting page.
		add_action( 'admin_menu', array( $this, 'register_option_page' ) );
		add_action( 'admin_init', array( $this, 'register_section_field' ) );

		// Register settings.
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Add links to plugin setting page and GitHub on wp-admin/plugins.php.
		add_filter(
			'plugin_action_links_' . ShowCurrentWidth_Core::PLUGIN_PREFIX . '/' . ShowCurrentWidth_Core::PLUGIN_PREFIX_DEPRECATED . '.php',
			array( $this, 'plugin_action_links' )
		);
	}

	/**
	 * Option page.
	 *
	 * @return void
	 */
	public function register_option_page() {
		// Page title, Menu title, Capability, Menu slug, Callback function.
		add_options_page(
			'Show Current Width',
			'Show Current Width',
			'manage_options',
			ShowCurrentWidth_Core::PLUGIN_PREFIX,
			array( $this, 'register_option_page_html' )
		);
	}

	/**
	 * Option page HTML.
	 *
	 * @return void
	 */
	public function register_option_page_html() {
		echo '<div class="wrap">';
		echo '<h1>Show Current Width</h1>';
		echo '<p>' . esc_html__( 'Screen width can be displayed in the WordPress admin bar.', 'show-current-width' ) . '</p>';
		echo '<form method="post" action="options.php">';
		settings_fields( ShowCurrentWidth_Core::PLUGIN_PREFIX . '-field1' );
		do_settings_sections( ShowCurrentWidth_Core::PLUGIN_PREFIX );
		submit_button();
		echo '</form>';
		echo '</div>';
	}

	/**
	 * Add section and field.
	 *
	 * @return void
	 */
	public function register_section_field() {
		// Add section 1.
		// ID, Title, Callback function, Setting page slug.
		add_settings_section(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section1',
			__( 'Breakpoint settings', 'show-current-width' ),
			array( $this, 'register_section1_html' ),
			ShowCurrentWidth_Core::PLUGIN_PREFIX
		);
		// Add field 1-1 (Display breakpoint names).
		// ID, Label, Callback function, Setting page slug, Section ID.
		add_settings_field(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_show',
			__( 'Display breakpoint names', 'show-current-width' ),
			array( $this, 'register_field_breakpoints_show_html' ),
			ShowCurrentWidth_Core::PLUGIN_PREFIX,
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section1',
			array(
				'label_for' => ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_show',
			),
		);
		// Add field 1-2 (Breakpoint definiiton).
		add_settings_field(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_definition',
			__( 'Breakpoint definiiton', 'show-current-width' ),
			array( $this, 'register_field_breakpoints_definition_html' ),
			ShowCurrentWidth_Core::PLUGIN_PREFIX,
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section1',
			array(
				'label_for' => ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_definition',
			),
		);
		// Add field 1-3.
		// a (Show icon in mobile screen).
		// b (Min width to show width icon).
		// c (Max width to show width icon).
		add_settings_field(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_limitwidth',
			__( 'Width limit to show screen width', 'show-current-width' ),
			array( $this, 'register_field_breakpoints_limitwidth_html' ),
			ShowCurrentWidth_Core::PLUGIN_PREFIX,
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section1',
			array(
				'label_for' => ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_limitwidth',
			),
		);

		// Add section 2.
		add_settings_section(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section2',
			__( 'Display settings', 'show-current-width' ),
			array( $this, 'register_section2_html' ),
			ShowCurrentWidth_Core::PLUGIN_PREFIX
		);
		// Add field 2-1 (Admin page display).
		add_settings_field(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_admin_show',
			__( 'Admin page display', 'show-current-width' ),
			array( $this, 'register_field_admin_show_html' ),
			ShowCurrentWidth_Core::PLUGIN_PREFIX,
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section2',
			array(
				'label_for' => ShowCurrentWidth_Core::PLUGIN_PREFIX . '_admin_show',
			),
		);
		// Add field 2-2 (Role condition).
		add_settings_field(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_condition_role',
			__( 'Role condition', 'show-current-width' ),
			array( $this, 'register_field_condition_role_html' ),
			ShowCurrentWidth_Core::PLUGIN_PREFIX,
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section2',
			array(
				'label_for' => ShowCurrentWidth_Core::PLUGIN_PREFIX . '_condition_role',
			),
		);
		// Add field 2-3 (Animation on/off).
		add_settings_field(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_animation_show',
			__( 'Animation', 'show-current-width' ),
			array( $this, 'register_field_animation_show_html' ),
			ShowCurrentWidth_Core::PLUGIN_PREFIX,
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section2',
			array(
				'label_for' => ShowCurrentWidth_Core::PLUGIN_PREFIX . '_animation_show',
			),
		);
		// Add field 2-4 (Animation timeout).
		add_settings_field(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_animation_timeout',
			__( 'Animation timeout', 'show-current-width' ),
			array( $this, 'register_field_animation_timeout_html' ),
			ShowCurrentWidth_Core::PLUGIN_PREFIX,
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section2',
			array(
				'label_for' => ShowCurrentWidth_Core::PLUGIN_PREFIX . '_animation_timeout',
			),
		);

		// Add section 3.
		add_settings_section(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section3',
			__( 'Other settings', 'show-current-width' ),
			array( $this, 'register_section3_html' ),
			ShowCurrentWidth_Core::PLUGIN_PREFIX
		);
		// Add field 3-1 (Init).
		add_settings_field(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_other_init',
			__( 'Initialize the settings', 'show-current-width' ),
			array( $this, 'register_field_other_init_html' ),
			ShowCurrentWidth_Core::PLUGIN_PREFIX,
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section3',
			array(
				'label_for' => ShowCurrentWidth_Core::PLUGIN_PREFIX . '_other_init',
			),
		);
		// Add field 3-2 (Uninstall).
		add_settings_field(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_other_uninstall',
			__( 'Delete the settings when uninstall', 'show-current-width' ),
			array( $this, 'register_field_other_uninstall_html' ),
			ShowCurrentWidth_Core::PLUGIN_PREFIX,
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section3',
			array(
				'label_for' => ShowCurrentWidth_Core::PLUGIN_PREFIX . '_other_uninstall',
			),
		);
	}

	/**
	 * Section 1 HTML.
	 *
	 * @return void
	 */
	public function register_section1_html() {
		echo esc_html__( 'Setting about breakpoints', 'show-current-width' );
	}

	/**
	 * Field 1-1 HTML.
	 *
	 * @return void
	 */
	public function register_field_breakpoints_show_html() {
		printf(
			'<input type="hidden" name="%s_breakpoints_show" value="0" />',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX )
		);
		printf(
			'<input type="checkbox" name="%s_breakpoints_show" id="%s_breakpoints_show" value="1" %s />',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			checked( get_option( ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_show' ), '1', false )
		);
		printf(
			'<label for="%s_breakpoints_show">%s</label>',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_html__( 'Display breakpoint names', 'show-current-width' )
		);
	}

	/**
	 * Field 1-2 HTML.
	 *
	 * @return void
	 */
	public function register_field_breakpoints_definition_html() {
		printf(
			'<textarea rows="8" cols="30" name="%s_breakpoints_definition" id="%s_breakpoints_definition">%s</textarea>',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_html( get_option( ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_definition' ) )
		);
		echo '<p>';
		echo '<code>';
		echo esc_html__( 'Min width, Max width, Breakpoint abbr, Breakpoint name', 'show-current-width' );
		echo '</code>';
		echo '</p>';
		echo '<p>';
		echo esc_html__( 'Each breakpoint is separated by a new line.', 'show-current-width' );
		echo '</p>';
	}

	/**
	 * Field 1-3 HTML.
	 *
	 * @return void
	 */
	public function register_field_breakpoints_limitwidth_html() {
		printf(
			'<input type="hidden" name="%s_breakpoints_limitwidth" value="0" />',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX )
		);
		printf(
			'<input type="checkbox" name="%s_breakpoints_limitwidth" id="%s_breakpoints_limitwidth" value="1" %s />',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			checked( get_option( ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_limitwidth' ), '1', false )
		);
		printf(
			'<label for="%s_breakpoints_limitwidth">%s</label>',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_html__( 'Show current width when the it is in the following range. ', 'show-current-width' )
		);
		printf(
			'<input type="text" name="%s_breakpoints_limitwidth_min" id="%s_breakpoints_limitwidth_min" class="small-text" value="%s" /> ~ ',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_attr( get_option( ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_limitwidth_min' ) )
		);
		printf(
			'<input type="text" name="%s_breakpoints_limitwidth_max" id="%s_breakpoints_limitwidth_max" class="small-text" value="%s" /> px',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_attr( get_option( ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_limitwidth_max' ) )
		);
	}

	/**
	 * Section 2 HTML.
	 *
	 * @return void
	 */
	public function register_section2_html() {
		echo esc_html__( 'Setting about display', 'show-current-width' );
	}

	/**
	 * Field 2-1 HTML.
	 *
	 * @return void
	 */
	public function register_field_admin_show_html() {
		printf(
			'<input type="hidden" name="%s_admin_show" value="0" />',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX )
		);
		printf(
			'<input type="checkbox" name="%s_admin_show" id="%s_admin_show" value="1" %s />',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			checked( get_option( ShowCurrentWidth_Core::PLUGIN_PREFIX . '_admin_show' ), '1', false )
		);
		printf(
			'<label for="%s_admin_show">%s</label>',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_html__( 'Display screen width on admin page', 'show-current-width' )
		);
	}

	/**
	 * Field 2-2 HTML.
	 *
	 * @return void
	 */
	public function register_field_condition_role_html() {
		global $wp_roles;
		$roles = $wp_roles->roles;
		echo esc_html__( 'Display width only for the user who has one of the following roles.', 'show-current-width' );
		echo '<ul>';
		foreach ( $roles as $role_key => $role_value ) {
			echo '<li>';
			printf(
				'<input type="checkbox" name="%s_condition_role[]" id="%s_condition_role_%s" value="%s" %s />',
				esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
				esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
				esc_attr( $role_key ),
				esc_attr( $role_key ),
				checked( in_array( $role_key, get_option( ShowCurrentWidth_Core::PLUGIN_PREFIX . '_condition_role' ), true ), true, false ),
			);
			printf(
				'<label for="%s_condition_role_%s">%s</label>',
				esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
				esc_attr( $role_key ),
				esc_attr( translate_user_role( $role_value['name'] ) )
			);
			echo '</li>';
		}
		echo '</ul>';
	}

	/**
	 * Field 2-3 HTML.
	 *
	 * @return void
	 */
	public function register_field_animation_show_html() {
		printf(
			'<input type="hidden" name="%s_animation_show" value="0" />',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX )
		);
		printf(
			'<input type="checkbox" name="%s_animation_show" id="%s_animation_show" value="1" %s />',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			checked( get_option( ShowCurrentWidth_Core::PLUGIN_PREFIX . '_animation_show' ), '1', false )
		);
		printf(
			'<label for="%s_animation_show">%s</label>',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_html__( 'Enable count up animation', 'show-current-width' )
		);
	}

	/**
	 * Field 2-4 HTML.
	 *
	 * @return void
	 */
	public function register_field_animation_timeout_html() {
		printf(
			'<label for="%s_animation_timeout">%s</label> ',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_html__( 'Time to display the screen width after it is changed. (millisecond)', 'show-current-width' )
		);
		printf(
			'<input type="text" name="%s_animation_timeout" id="%s_animation_timeout" class="small-text" value="%s" /> ms',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_attr( get_option( ShowCurrentWidth_Core::PLUGIN_PREFIX . '_animation_timeout' ) )
		);
	}

	/**
	 * Section 3 HTML.
	 *
	 * @return void
	 */
	public function register_section3_html() {
		echo esc_html__( 'Other settings which are not included in the above', 'show-current-width' );
	}

	/**
	 * Field 3-1 HTML.
	 *
	 * @return void
	 */
	public function register_field_other_init_html() {
		printf(
			'<input type="hidden" name="%s_other_init" value="0" />',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX )
		);
		printf(
			'<input type="checkbox" name="%s_other_init" id="%s_other_init" value="1" %s />',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			checked( get_option( ShowCurrentWidth_Core::PLUGIN_PREFIX . '_other_init' ), '1', false )
		);
		printf(
			'<label for="%s_other_init">%s</label>',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_html__( 'Initialize all the settings to default values', 'show-current-width' )
		);
	}

	/**
	 * Field 3-2 HTML.
	 *
	 * @return void
	 */
	public function register_field_other_uninstall_html() {
		printf(
			'<input type="hidden" name="%s_other_uninstall" value="0" />',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX )
		);
		printf(
			'<input type="checkbox" name="%s_other_uninstall" id="%s_other_uninstall" value="1" %s />',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			checked( get_option( ShowCurrentWidth_Core::PLUGIN_PREFIX . '_other_uninstall' ), '1', false )
		);
		printf(
			'<label for="%s_other_uninstall">%s</label>',
			esc_attr( ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_html__( 'Delete all the settings when this plugin is uninstalled', 'show-current-width' )
		);
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		// Group name, input name, Sanitize function.
		register_setting(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-field1',
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_definition',
			'esc_html',
		);
		register_setting(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-field1',
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_show',
			'esc_attr',
		);
		register_setting(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-field1',
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_limitwidth',
			'esc_attr',
		);
		register_setting(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-field1',
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_limitwidth_min',
			'esc_attr',
		);
		register_setting(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-field1',
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_limitwidth_max',
			'esc_attr',
		);
		register_setting(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-field1',
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_admin_show',
			'esc_attr',
		);
		register_setting(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-field1',
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_condition_role',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_array' ),
			),
		);
		register_setting(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-field1',
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_animation_show',
			'esc_attr',
		);
		register_setting(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-field1',
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_animation_timeout',
			'esc_attr',
		);
		register_setting(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-field1',
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_other_init',
			'esc_attr',
		);
		register_setting(
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '-field1',
			ShowCurrentWidth_Core::PLUGIN_PREFIX . '_other_uninstall',
			'esc_attr',
		);
	}

	/**
	 * Sanitize array.
	 *
	 * @param array $args array to be sanitized.
	 * @return array array sanitized.
	 */
	public function sanitize_array( $args ) {
		$args = isset( $args ) ? (array) $args : array();
		$args = array_map( 'esc_attr', $args );
		return $args;
	}

	/**
	 * Add links to plugin setting page and GitHub on wp-admin/plugins.php.
	 *
	 * @param array $actions Links for action.
	 * @return array Links for action.
	 */
	public function plugin_action_links( $actions ) {
		$link_setting = sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'options-general.php?page=' . ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			__( 'Settings', 'show-current-width' )
		);
		$link_github  = sprintf(
			'<a href="%s">%s</a>',
			ShowCurrentWidth_Core::PLUGIN_GITHUB,
			'GitHub'
		);
		array_unshift( $actions, $link_setting, $link_github );
		return $actions;
	}
}
