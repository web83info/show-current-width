<?php
/**
 * W83ShowCurrentWidth_Admin
 *
 * @package Show_Current_Width
 */

namespace W83ShowCurrentWidth;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin class
 */
class W83ShowCurrentWidth_Admin {

	// Singleton trait.
	use Singleton;

	/**
	 * Plugin constant.
	 *
	 * @return void
	 */
	private function __construct() {
		// Plugin setting page.
		add_action( 'admin_menu', array( $this, 'register_option_page' ) );
		add_action( 'admin_init', array( $this, 'register_section_field' ) );

		// Register settings.
		add_action( 'admin_init', array( $this, 'register_settings' ) );
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
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX,
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
		echo '<p>' . esc_html__( 'Screen width can be displayed in the WordPress admin bar.', 'w83-show-current-width' ) . '</p>';
		echo '<form method="post" action="options.php">';
		settings_fields( W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '-field1' );
		do_settings_sections( W83ShowCurrentWidth_Core::PLUGIN_PREFIX );
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
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section1',
			__( 'Breakpoint settings', 'w83-show-current-width' ),
			array( $this, 'register_section1_html' ),
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX
		);
		// Add field 1-1 (Display breakpoint names).
		// ID, Label, Callback function, Setting page slug, Section ID.
		add_settings_field(
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_show',
			__( 'Display breakpoint names', 'w83-show-current-width' ),
			array( $this, 'register_field_breakpoints_show_html' ),
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX,
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section1',
			array(
				'label_for' => W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_show',
			),
		);
		// Add field 1-2 (Breakpoint definiiton).
		add_settings_field(
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_definition',
			__( 'Breakpoint definiiton', 'w83-show-current-width' ),
			array( $this, 'register_field_breakpoints_definition_html' ),
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX,
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section1',
			array(
				'label_for' => W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_definition',
			),
		);

		// Add section 2.
		add_settings_section(
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section2',
			__( 'Admin page settings', 'w83-show-current-width' ),
			array( $this, 'register_section2_html' ),
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX
		);
		// Add field 2-1 (Admin page display).
		add_settings_field(
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '_admin_show',
			__( 'Admin page display', 'w83-show-current-width' ),
			array( $this, 'register_field_admin_show_html' ),
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX,
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section2',
			array(
				'label_for' => W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '_admin_show',
			),
		);

		// Add section 3.
		add_settings_section(
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section3',
			__( 'Other settings', 'w83-show-current-width' ),
			array( $this, 'register_section3_html' ),
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX
		);
		// Add field 3-1 (Init).
		add_settings_field(
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '_other_init',
			__( 'Initialize the settings', 'w83-show-current-width' ),
			array( $this, 'register_field_other_init_html' ),
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX,
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '-section3',
			array(
				'label_for' => W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '_other_init',
			),
		);
	}

	/**
	 * Section 1 HTML.
	 *
	 * @return void
	 */
	public function register_section1_html() {
		echo esc_html__( 'Setting about breakpoints', 'w83-show-current-width' );
	}

	/**
	 * Field 1-1 HTML.
	 *
	 * @return void
	 */
	public function register_field_breakpoints_show_html() {
		printf(
			'<input type="hidden" name="%s_breakpoints_show" value="0" />',
			esc_attr( W83ShowCurrentWidth_Core::PLUGIN_PREFIX )
		);
		printf(
			'<input type="checkbox" name="%s_breakpoints_show" id="%s_breakpoints_show" value="1" %s />',
			esc_attr( W83ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_attr( W83ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			checked( get_option( W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_show' ), '1', false )
		);
		printf(
			'<label for="%s_breakpoints_show">%s</label>',
			esc_attr( W83ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_html__( 'Display breakpoint names', 'w83-show-current-width' )
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
			esc_attr( W83ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_attr( W83ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_html( get_option( W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_definition' ) )
		);
		echo '<p>';
		echo __( '<code>Min width, Max width, Breakpoint abbr, Breakpoint name</code> Comma-separated values, no space between two values.', 'w83-show-current-width' );
		echo __( 'Each breakpoint is separated by a new line.', 'w83-show-current-width' );
		echo '</p>';
	}

	/**
	 * Section 2 HTML.
	 *
	 * @return void
	 */
	public function register_section2_html() {
		echo esc_html__( 'Setting about admin page', 'w83-show-current-width' );
	}

	/**
	 * Field 2-1 HTML.
	 *
	 * @return void
	 */
	public function register_field_admin_show_html() {
		printf(
			'<input type="hidden" name="%s_admin_show" value="0" />',
			esc_attr( W83ShowCurrentWidth_Core::PLUGIN_PREFIX )
		);
		printf(
			'<input type="checkbox" name="%s_admin_show" id="%s_admin_show" value="1" %s />',
			esc_attr( W83ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_attr( W83ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			checked( get_option( W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '_admin_show' ), '1', false )
		);
		printf(
			'<label for="%s_admin_show">%s</label>',
			esc_attr( W83ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_html__( 'Display screen width on admin page', 'w83-show-current-width' )
		);
	}

	/**
	 * Section 3 HTML.
	 *
	 * @return void
	 */
	public function register_section3_html() {
		echo esc_html__( 'Other settings which are not included in the above', 'w83-show-current-width' );
	}

	/**
	 * Field 3-1 HTML.
	 *
	 * @return void
	 */
	public function register_field_other_init_html() {
		printf(
			'<input type="hidden" name="%s_other_init" value="0" />',
			esc_attr( W83ShowCurrentWidth_Core::PLUGIN_PREFIX )
		);
		printf(
			'<input type="checkbox" name="%s_other_init" id="%s_other_init" value="1" %s />',
			esc_attr( W83ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_attr( W83ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			checked( get_option( W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '_other_init' ), '1', false )
		);
		printf(
			'<label for="%s_other_init">%s</label>',
			esc_attr( W83ShowCurrentWidth_Core::PLUGIN_PREFIX ),
			esc_html__( 'Initialize all the settings to default values', 'w83-show-current-width' )
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
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '-field1',
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_definition',
			'esc_html',
		);
		register_setting(
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '-field1',
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '_breakpoints_show',
			'esc_attr',
		);
		register_setting(
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '-field1',
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '_admin_show',
			'esc_attr',
		);
		register_setting(
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '-field1',
			W83ShowCurrentWidth_Core::PLUGIN_PREFIX . '_other_init',
			'esc_attr',
		);
	}
}
