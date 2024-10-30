<?php
// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class LR_Formidable
 *
 * @since  1.0.0
 * @author Dor Zuberi <me@dorzki.co.il>
 */
class LR_Formidable {

	/**
	 * Initiates integration.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function integration_init() {

		if ( ! class_exists( 'FrmFormAction' ) ) {
			return;
		}

		include_once( LR_PLUGIN_DIR . '/classes/integrations/formidable-addon.php' );

		add_action( 'frm_registered_form_actions', array( $this, 'register_integration' ) );

	}

	/**
	 * Register the integration.
	 *
	 * @param $actions
	 *
	 * @return mixed
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function register_integration( $actions ) {

		$actions[ 'leadsrocks' ] = 'LR_Formidable_Addon';

		return $actions;

	}

}