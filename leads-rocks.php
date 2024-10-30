<?php
/**
 * Plugin Name: Leads Rocks! for WordPress
 * Plugin URI: https://wordpress.leads.rocks
 * Description: Enables integration between Leads Rocks and WordPress for managing and collecting leads.
 * Author: Dor Zuberi
 * Author URI: https://www.dorzki.co.il
 * Version: 1.0.0
 * Text Domain: leads-rocks
 * License: GPL3
 *
 * @package   Leads Rocks
 * @since     1.0.0
 * @version   1.0.0
 * @author    Dor Zuberi <me@dorzki.co.il>
 * @link      https://www.dorzki.co.il
 */

// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Plugin variables.
if ( ! defined( 'LR_PLUGIN_URL' ) ) {
	define( 'LR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'LR_PLUGIN_DIR' ) ) {
	define( 'LR_PLUGIN_DIR', dirname( __FILE__ ) );
}

if ( ! defined( 'LR_API_URL' ) ) {
	define( 'LR_API_URL', 'https://api.leads.rocks' );
}


// Plugin files.
include_once( 'classes/lr-admin.php' );
include_once( 'classes/lr-auth.php' );
include_once( 'classes/lr-campaigns.php' );
include_once( 'classes/lr-lead.php' );
include_once( 'classes/integrations/contact-form7.php' );
include_once( 'classes/integrations/gravity-forms.php' );
include_once( 'classes/integrations/formidable.php' );
include_once( 'classes/integrations/ninja-forms.php' );
include_once( 'classes/integrations/pojo-forms.php' );


/**
 * Class Leads_Rocks
 *
 * @since  1.0.0
 * @author Dor Zuberi <me@dorzki.co.il>
 */
class Leads_Rocks {

	/**
	 * The current plugin instance.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Plugin slug name.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $plugin_slug;

	/**
	 * Plugin version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $plugin_version;


	/**
	 * Leads_Rocks constructor.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	protected function __construct() {

		// Plugin identity
		$this->plugin_slug    = 'leads-rocks';
		$this->plugin_version = '1.0.0';

		// Session Handler
		if ( ! session_id() ) {
			session_start();
		}

		// Classes
		new LR_Admin;

		// Integrations
		add_action( 'leads_rocks_integrations_init', array( new LR_ContactForm7, 'integration_init' ) );
		add_action( 'leads_rocks_integrations_init', array( new LR_GravityForms, 'integration_init' ) );
		add_action( 'leads_rocks_integrations_init', array( new LR_Formidable, 'integration_init' ) );
		add_action( 'leads_rocks_integrations_init', array( new LR_NinjaForms, 'integration_init' ) );
		add_action( 'leads_rocks_integrations_init', array( new LR_PojoForms, 'integration_init' ) );

		// Hooks
		add_action( 'init', array( &$this, 'utm_grabber' ) );
		add_action( 'plugins_loaded', array( &$this, 'set_locale' ) );
		add_action( 'plugins_loaded', array( &$this, 'load_integrations' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'register_admin_assets' ) );

	}


	/**
	 * Returns the current instance of the plugin.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 * @return Leads_Rocks|object
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {

			self::$instance = new self;

		}

		return self::$instance;

	}


	/**
	 * Enables i18n of the plugin.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function set_locale() {

		load_plugin_textdomain( $this->plugin_slug, false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Saves the UTM fields to current user session.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function utm_grabber() {

		// UTM fields list.
		$utm_fields = array( 'utm_campaign', 'utm_content', 'utm_term', 'utm_medium', 'utm_source' );

		foreach ( $utm_fields as $utm_field ) {

			if ( ! isset( $_SESSION[ $utm_field ] ) && isset( $_GET[ $utm_field ] ) ) {
				$_SESSION[ $utm_field ] = $_GET[ $utm_field ];
			}

		}

	}

	/**
	 * Register plugin assets.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function register_admin_assets() {

		# CSS
		wp_enqueue_style( 'leadsrocks-admin', LR_PLUGIN_URL . 'assets/css/style.css' );

	}

	/**
	 * Load Leads Rocks! integrations.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function load_integrations() {

		do_action( 'leads_rocks_integrations_init' );

	}

	/**
	 * Throw error on object clone
	 *
	 * @since 1.0.0
	 */
	public function __clone() {

		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Cheatin&#8217; huh?', 'leads-rocks' ) ), '1.0.0' );

	}


	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {

		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Cheatin&#8217; huh?', 'leads-rocks' ) ), '1.0.0' );

	}

}

// Initiate the plugin
Leads_Rocks::get_instance();