<?php
// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class LR_Formidable_Addon
 *
 * @since  1.0.0
 * @author Dor Zuberi <me@dorzki.co.il>
 */
class LR_Formidable_Addon extends FrmFormAction {

	/**
	 * LR_Formidable_Addon constructor.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function __construct() {

		$action_ops = array(
			'classes'  => 'leadsrocks_formidable',
			'limit'    => 99,
			'active'   => true,
			'priority' => 50,
		);

		add_action( 'frm_trigger_leadsrocks_create_action', array( $this, 'send_lead' ), 10, 3 );

		parent::__construct( 'leadsrocks', esc_html__( 'Leads Rocks!', 'leads-rocks' ), $action_ops );

	}

	/**
	 * Prints integration panel HTML.
	 *
	 * @param array $form_action
	 * @param array $args
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function form( $form_action, $args = array() ) {

		extract( $args );
		$fields_values = $form_action->post_content;
		$form_fields   = FrmField::getAll( 'fi.form_id=' . (int) $args[ 'form' ]->id . " AND fi.type NOT IN ('break', 'divider', 'end_divider', 'html', 'captcha', 'form')", 'field_order' );

		$campaigns        = LR_Campaign::get_campaigns();
		$campaign_id      = $fields_values[ 'campaign' ];
		$current_campaign = LR_Campaign::get_campaign_data( $campaign_id );

		include_once( LR_PLUGIN_DIR . '/templates/formidable-panel.php' );

	}

	/**
	 * Process the form and send to Leads Rocks!
	 *
	 * @param $action
	 * @param $entry
	 * @param $form
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function send_lead( $action, $entry, $form ) {

		$fields_values = $action->post_content;

		$campaign = $fields_values[ 'campaign' ];
		$fields   = array();

		// Extract fields.
		foreach ( $fields_values as $field => $field_id ) {

			if ( $field === 'campaign' || $field === 'event' || $field === 'conditions' ) {
				continue;
			}

			$fields[ $field ] = $entry->metas[ $field_id ];

		}

		new LR_Lead( $campaign, $fields );

	}

}