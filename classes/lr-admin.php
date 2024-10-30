<?php
// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class LR_Admin
 *
 * @since  1.0.0
 * @author Dor Zuberi <me@dorzki.co.il>
 */
class LR_Admin {

	/**
	 * Plugin menu icon in base64 format.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private $menu_icon = "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHZlcnNpb249IjEuMSIgaWQ9Il94MzlfOTI1ZWQyYS0yZmJiLTRlNzYtODAzYS1lODhkZDZkYWU0NzciIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMTk2IDE2OC41IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAxOTYgMTY4LjU7IiB4bWw6c3BhY2U9InByZXNlcnZlIj48dGl0bGU+bG9nby1zeW1ib2w8L3RpdGxlPjxwYXRoIHN0eWxlPSJmaWxsOiNBMEE1QUE7IiBkPSJNOTEuMiwxMDkuM2MwLjYsMjguOSwyNiw0OS42LDM0LjUsNTUuOWw0LjQsMy4zbDUuNS0zLjNjMTAuMy02LjMsNDIuMy0yNy44LDUwLjItNTUuOWMwLjctMi43LDEuMi01LjQsMS42LTguMWw4LjQtNTkuOWMwLjEtMSwwLjItMi4xLDAuMS0zLjJjLTAuMy03LjgtNi44LTEzLjktMTQuNi0xMy43Yy04LDAtMTUuOCw1LjctMTguNCwxMy42Yy0wLjQsMS0wLjYsMi4xLTAuOCwzLjJsLTEuNywxMmMtMS4zLTAuMS0yLjUtMC4yLTMuOC0wLjJjLTMuNi0wLjEtNy4yLDAuNi0xMC42LDJjLTMuMS0xLjQtNi42LTIuMS0xMC0yYy0xLjMsMC0yLjYsMC4xLTMuOCwwLjJsNS4xLTM2LjRjMC4xLTEsMC4yLTIsMC4xLTMuMXYtMC4xYy0wLjMtNy44LTYuOC0xMy45LTE0LjYtMTMuN2MtOCwwLTE1LjgsNS44LTE4LjQsMTMuNmMtMC40LDEtMC42LDIuMS0wLjgsMy4ybC04LjksNjMuMWwtMS4yLDguMWwtMi4xLDE0LjdDOTEuMywxMDQuOSw5MS4xLDEwNy4xLDkxLjIsMTA5LjN6IE0xNDAuMyw4NC44aC0xNy40bDIuNy0xOWMwLjEtMC42LDAuMi0xLjEsMC40LTEuNmMwLjctMiw0LjktMyw5LTNzOCwxLDguMSwzbDAsMGMwLDAuNSwwLDEtMC4xLDEuNmwwLDBsLTIuNiwxOC42TDE0MC4zLDg0Ljh6IE0xNjAuOSw4NC44aC0xNy40di0wLjRsMi42LTE4LjZsMC4yLTEuNmwwLDBsLTAuMiwxLjZjMC4xLTAuNSwwLjItMS4xLDAuNC0xLjZsMCwwYzAuNy0yLDQuOS0zLDktM3M4LDEsOC4xLDNjMCwwLjUsMCwxLjEtMC4xLDEuNkwxNjAuOSw4NC44eiBNMTAxLjgsODguMWg1OC42YzAsMC4yLTAuMSwwLjUtMC4yLDAuN2MtMC4zLDEuMS0wLjcsMi4yLTEuMiwzLjNjLTMuNCw3LjUtMTAuNiwxMi42LTE4LjgsMTMuM2gtMjMuNWMtMC45LDAtMS43LDAuNy0xLjksMS42Yy0wLjEsMC44LDAuNCwxLjUsMS4xLDEuNmMwLDAsMCwwLDAsMGgwLjNoMjMuNmMzLjgtMC4zLDcuNS0xLjQsMTAuOC0zLjNjMy4yLTEuOCw2LjEtNC4zLDguNC03LjNjMS0xLjQsMS45LTIuOCwyLjctNC40YzAuNS0xLjEsMS0yLjIsMS4zLTMuM2MwLjItMC42LDAuNC0xLjIsMC41LTEuOGwwLjItMC44YzAuMS0wLjQsMy4xLTIyLDMuMS0yMmwwLjItMS42bDMuMi0yMi45YzAuMS0wLjYsMC4yLTEuMSwwLjQtMS42YzEuNC00LjEsNS4yLTYuOSw5LjUtNy4xYzQtMC4xLDcuNCwzLDcuNSw3LjFjMCwwLjUsMCwxLjEtMC4xLDEuNmMtMC4xLDAuNi04LjgsNjIuNi04LjksNjMuMWMtNS41LDMwLjEtNDYuOCw1NC00Ny4yLDU0LjNjLTAuMy0wLjMtMzUtMjQuMi0zMi01NC4zYzAuMS0wLjUsMC4xLTEuMSwwLjItMS42TDEwMS44LDg4LjF6IE0xMTEuOCwxNi44YzAuMS0wLjYsMC4yLTEuMSwwLjQtMS42YzEuNC00LjEsNS4yLTYuOSw5LjUtNy4xYzQtMC4xLDcuNCwzLDcuNSw3LjFjMCwwLjUsMCwxLjEtMC4xLDEuNmwtNi43LDQ3LjRsLTAuMiwxLjZsLTIuNywxOWgtMTcuM0wxMTEuOCwxNi44eiIvPjxwYXRoIHN0eWxlPSJmaWxsOiNBMEE1QUE7IiBkPSJNODQuMyw2My42SDMuNmMtMS45LDAuMS0zLjUtMS40LTMuNi0zLjNjMC0wLjMsMC0wLjUsMC4xLTAuOGMwLjQtMi4zLDIuMy00LDQuNy00LjFoODAuN2MxLjktMC4xLDMuNSwxLjQsMy42LDMuNGMwLDAuMiwwLDAuNS0wLjEsMC43Qzg4LjUsNjEuOCw4Ni42LDYzLjUsODQuMyw2My42eiIvPjxwYXRoIHN0eWxlPSJmaWxsOiNBMEE1QUE7IiBkPSJNODEuNyw4MS45SDE5Yy0xLjksMC4xLTMuNS0xLjQtMy42LTMuNGMwLTAuMiwwLTAuNSwwLjEtMC43YzAuNC0yLjMsMi4zLTQsNC42LTQuMWg2Mi44YzEuOS0wLjEsMy41LDEuNCwzLjYsMy40YzAsMC4yLDAsMC41LTAuMSwwLjdDODYsODAuMiw4NCw4MS45LDgxLjcsODEuOXoiLz48cGF0aCBzdHlsZT0iZmlsbDojQTBBNUFBOyIgZD0iTTc5LjEsMTAwLjNIMzUuNWMtMS45LDAuMS0zLjUtMS40LTMuNi0zLjRjMC0wLjIsMC0wLjUsMC4xLTAuN2MwLjQtMi4zLDIuMy00LDQuNi00LjFoNDMuNmMxLjktMC4xLDMuNSwxLjQsMy42LDMuNGMwLDAuMiwwLDAuNS0wLjEsMC43QzgzLjQsOTguNSw4MS40LDEwMC4zLDc5LjEsMTAwLjN6Ii8+PHBhdGggc3R5bGU9ImZpbGw6I0EwQTVBQTsiIGQ9Ik03Ni41LDExOC43SDU0LjdjLTEuOSwwLjEtMy41LTEuNS0zLjYtMy40YzAtMC4yLDAtMC41LDAuMS0wLjdjMC40LTIuMywyLjMtNCw0LjctNC4xaDIxLjhjMS45LTAuMSwzLjUsMS40LDMuNiwzLjRjMCwwLjIsMCwwLjUtMC4xLDAuN0M4MC44LDExNi45LDc4LjksMTE4LjYsNzYuNSwxMTguN3oiLz48L3N2Zz4=";

	/**
	 * Plugin settings sections descriptions.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private $sections_desc;


	/**
	 * LR_Admin constructor.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function __construct() {

		$this->sections_desc = array(
			'leads-rocks_auth' => __( 'Enter your Leads Rocks! login credentials in order to activate the plugin.', 'leads-rocks' )
		);

		add_action( 'admin_menu', array( &$this, 'register_menus' ) );
		add_action( 'admin_init', array( &$this, 'register_fields' ) );

	}

	/**
	 * Register plugin admin menus.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function register_menus() {

		add_menu_page( esc_html__( 'Leads Rocks! for WordPress', 'leads-rocks' ), esc_html__( 'Leads Rocks!', 'leads-rocks' ), 'manage_options', 'leads-rocks', array(
			&$this,
			'settings_page_main'
		), $this->menu_icon, 3 );

	}

	/**
	 * Register plugin settings fields & sections.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function register_fields() {

		// Register Settings
		register_setting( 'leads-rocks', 'leadsrocks_options' );

		// Register Sections
		add_settings_section( 'leads-rocks_auth', esc_html__( 'Authentication', 'leads-rocks' ), array(
			&$this,
			'settings_section_cb'
		), 'leads-rocks' );

		// Register Fields
		add_settings_field( 'leads-rocks_auth_username', esc_html__( 'Username', 'leads-rocks' ), array(
			&$this,
			'settings_field_cb'
		), 'leads-rocks', 'leads-rocks_auth', array(
			'label_for'  => 'leads-rocks_auth_username',
			'field_type' => 'email'
		) );

		add_settings_field( 'leads-rocks_auth_password', esc_html__( 'Password', 'leads-rocks' ), array(
			&$this,
			'settings_field_cb'
		), 'leads-rocks', 'leads-rocks_auth', array(
			'label_for'  => 'leads-rocks_auth_password',
			'field_type' => 'password'
		) );

	}

	/**
	 * Settings section HTML.
	 *
	 * @param $args
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il)
	 */
	public function settings_section_cb( $args ) {

		printf( '<p id="%s">%s</p>', esc_attr( $args[ 'id' ] ), esc_html( $this->sections_desc[ $args[ 'id' ] ] ) );

	}

	/**
	 * Settings field HTML.
	 *
	 * @param $args
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function settings_field_cb( $args ) {

		$options     = get_option( 'leadsrocks_options' );
		$field_value = ( isset( $options[ $args[ 'label_for' ] ] ) ) ? $options[ $args[ 'label_for' ] ] : '';

		printf( '<input type="%s" id="%s" name="leadsrocks_options[%s]" value="%s">', esc_attr( $args[ 'field_type' ] ), esc_attr( $args[ 'label_for' ] ), esc_attr( $args[ 'label_for' ] ), $field_value );

	}

	/**
	 * Plugin main page HTML.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function settings_page_main() {

		// Protect the settings page.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Add settings saved message.
		if ( isset( $_GET[ 'settings-updated' ] ) ) {
			add_settings_error( 'leads-rocks_messages', 'leads-rocks_message', esc_html__( 'Settings Saved', 'leads-rocks' ), 'updated' );
		}

		// Include page HTML.
		include_once( LR_PLUGIN_DIR . '/templates/admin-main.php' );

	}

}