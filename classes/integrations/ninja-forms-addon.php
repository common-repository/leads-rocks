<?php
// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class LR_NinjaForms_Addon
 *
 * @since  1.0.0
 * @author Dor Zuberi <me@dorzki.co.il>
 */
class LR_NinjaForms_Addon extends NF_Abstracts_Action {

	/**
	 * Action ID.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected $_name = 'leadsrocks';

	/**
	 * Search keywords.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected $_tags = array( 'crm', 'leads', 'action' );


	/**
	 * LR_NinjaForms_Addon constructor.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function __construct() {

		parent::__construct();

		$this->_nicename = esc_html__( 'Leads Rocks!', 'leads-rocks' );
		$this->_image    = LR_PLUGIN_URL . '/assets/images/menu-logo-hover.svg';
		$this->_settings = array_merge( $this->_settings, $this->get_action_fields() );

	}

	/**
	 * Generate fields list.
	 *
	 * @return array
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	private function get_action_fields() {

		$action_fields = array();
		$campaigns     = LR_Campaign::get_campaigns();

		$action_fields[ 'campaign' ] = array(
			'name'    => 'campaign',
			'type'    => 'select',
			'options' => array(
				array( 'label' => esc_html__( 'Select campaign', 'leads-rocks' ), 'value' => -1 )
			),
			'group'   => 'primary',
			'label'   => esc_html__( 'Campaign', 'leads-rocks' ),
			'width'   => 'full'
		);

		foreach ( $campaigns as $campaign ) {
			$action_fields[ 'campaign' ][ 'options' ][] = array(
				'label' => esc_html( $campaign->name ),
				'value' => esc_attr( $campaign->_id )
			);

			foreach ( $campaign->fields as $field ) {
				$action_fields[ $campaign->_id . '_' . $field->field ] = array(
					'name'           => esc_html( $campaign->_id . '_' . $field->field ),
					'type'           => 'textbox',
					'group'          => 'primary',
					'label'          => esc_html( $field->title ),
					'width'          => 'one-half',
					'use_merge_tags' => true,
					'deps'           => array(
						'campaign' => $campaign->_id
					)
				);
			}

		}

		return $action_fields;

	}

	/**
	 * Shell function.
	 *
	 * @param $action_settings
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function save( $action_settings ) {
		// Do nothing...
	}

	/**
	 * Process the form and send to Leads Rocks!
	 *
	 * @param $action_settings
	 * @param $form_id
	 * @param $data
	 *
	 * @return mixed
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function process( $action_settings, $form_id, $data ) {

		$fields           = array();
		$campaign         = $action_settings[ 'campaign' ];
		$current_campaign = LR_Campaign::get_campaign_data( $campaign );

		foreach ( $current_campaign->fields as $field ) {
			$fields[ $field->field ] = $action_settings[ $campaign . '_' . $field->field ];
		}

		new LR_Lead( $campaign, $fields );

		return $data;
	}

}