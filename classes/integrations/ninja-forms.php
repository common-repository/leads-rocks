<?php
// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class LR_NinjaForms
 *
 * @since  1.0.0
 * @author Dor Zuberi <me@dorzki.co.il>
 */
class LR_NinjaForms {

	/**
	 * Initiates integration.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function integration_init() {

		if ( version_compare( get_option( 'ninja_forms_version', '0.0.0' ), '3.0', '>=' ) && ! get_option( 'ninja_forms_load_deprecated', false ) ) {

			if ( ! class_exists( 'NF_Abstracts_Action' ) ) {
				return;
			}

			include_once( LR_PLUGIN_DIR . '/classes/integrations/ninja-forms-addon.php' );

			add_filter( 'ninja_forms_register_actions', array( $this, 'register_integration' ) );

		} else {

			if ( ! class_exists( 'NF_Notification_Base_Type' ) ) {
				return;
			}

			include_once( LR_PLUGIN_DIR . '/classes/integrations/ninja-forms-addon-deprecated.php' );

			add_filter( 'nf_notification_types', array( $this, 'register_integration_deprecated' ) );

		}

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

		$actions[ 'leadsrocks' ] = new LR_NinjaForms_Addon;

		return $actions;

	}

	/**
	 * Register the integration for old versions (below 3.0.0).
	 *
	 * @param $actions
	 *
	 * @return mixed
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function register_integration_deprecated( $actions ) {

		$actions[ 'leadsrocks' ] = new LR_NinjaForms_Addon_Dep;

		return $actions;

	}

}