<?php
// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class LR_NinjaForms_Addon_Dep
 *
 * Support for versions below 3.
 *
 * @since  1.0.0
 * @author Dor Zuberi <me@dorzki.co.il>
 */
class LR_NinjaForms_Addon_Dep extends NF_Notification_Base_Type {

	/**
	 * LR_NinjaForms_Addon_Dep constructor.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function __construct() {

		$this->name = esc_html__( 'Leads Rocks!', 'leads-rocks' );

	}

	/**
	 * Prints integration panel HTML.
	 *
	 * @param string $id
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function edit_screen( $id = '' ) {

		$form_id     = ( '' != $id ) ? Ninja_Forms()->notification( $id )->form_id : '';
		$campaigns   = LR_Campaign::get_campaigns();
		$campaign_id = '';

		if ( ! empty( $form_id ) ) {

			$campaign_id      = Ninja_Forms()->notification( $id )->get_setting( 'campaign' );
			$current_campaign = LR_Campaign::get_campaign_data( $campaign_id );

		}

		include_once( LR_PLUGIN_DIR . '/templates/ninja-forms-panel-deprecated.php' );

	}

	/**
	 * Retrieves the tag values to display on the field.
	 *
	 * @param $id
	 * @param $meta_key
	 * @param $form_id
	 *
	 * @return array
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function get_value( $id, $meta_key, $form_id ) {

		$meta_value = nf_get_object_meta_value( $id, $meta_key );
		$meta_value = explode( '`', $meta_value );

		$return = array();
		foreach ( $meta_value as $val ) {

			if ( strpos( $val, 'field_' ) !== false ) {
				$val   = str_replace( 'field_', '', $val );
				$label = nf_get_field_admin_label( $val, $form_id );
				if ( strlen( $label ) > 30 ) {
					$label = substr( $label, 0, 30 );
				}

				$return[] = array( 'value' => esc_attr( 'field_' . $val ), 'label' => esc_html( $label . ' - ID: ' . $val ) );

			} else {

				$return[] = array( 'value' => esc_attr( $val ), 'label' => esc_html( $val ) );

			}

		}

		return $return;

	}

	/**
	 * Process the form and send to Leads Rocks!
	 *
	 * @param $id
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function process( $id ) {

		global $ninja_forms_processing;

		// Get field ids.
		$campaign         = Ninja_Forms()->notification( $id )->get_setting( 'campaign' );
		$current_campaign = LR_Campaign::get_campaign_data( $campaign );
		$fields           = array();

		// Get field data.
		foreach ( $current_campaign->fields as $field ) {

			$field_id                = str_replace( 'field_', '', Ninja_Forms()->notification( $id )->get_setting( 'field_' . $field->field ) );
			$fields[ $field->field ] = $ninja_forms_processing->get_field_value( $field_id );

		}

		new LR_Lead( $campaign, $fields );

	}

}