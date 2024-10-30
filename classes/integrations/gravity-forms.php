<?php
// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class LR_GravityForms
 *
 * @since  1.0.0
 * @author Dor Zuberi <me@dorzki.co.il>
 */
class LR_GravityForms {

	/**
	 * LR_ContactForm7 constructor.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function __construct() {

		add_action( 'gform_loaded', array( &$this, 'integration_init' ), 5 );

	}

	/**
	 * Register integration.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function integration_init() {

		if ( ! class_exists( 'GFForms' ) ) {
			return;
		}

		include_once( LR_PLUGIN_DIR . '/classes/integrations/gravity-forms-addon.php' );

		GFAddOn::register( 'LR_GravityForms_Addon' );

	}

}