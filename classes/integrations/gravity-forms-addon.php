<?php
// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}


GFForms::include_feed_addon_framework();

/**
 * Class LR_GravityForms_Addon
 *
 * @since  1.0.0
 * @author Dor Zuberi <me@dorzki.co.il>
 */
class LR_GravityForms_Addon extends GFFeedAddOn {

	/**
	 * Addon Version
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected $_version = '1.0.0';

	/**
	 * Minimum required Gravity Forms version.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected $_min_gravityforms_version = '1.9';

	/**
	 * Addon slug.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected $_slug = 'leadsrocks';

	/**
	 * Addon bootstrap file.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected $_path = 'leads-rocks/leads-rocks.php';

	/**
	 * Addon class path.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected $_full_path = __FILE__;

	/**
	 * Addon name.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected $_title;

	/**
	 * Addon short name.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected $_short_title;

	/**
	 * Addon class instance.
	 *
	 * @var null
	 * @since 1.0.0
	 */
	private static $_instance = null;


	/**
	 * Addon initialization process.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function init() {

		parent::init();

		$this->_title       = esc_html__( 'Leads Rocks! for Gravity Forms', 'leads-rocks' );
		$this->_short_title = esc_html__( 'Leads Rocks!', 'leads-rocks' );

	}

	/**
	 * Retrieve addon instance.
	 *
	 * @return LR_GravityForms|null
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public static function get_instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self;
		}

		return self::$_instance;

	}

	/**
	 * Generates a list of feed settings.
	 *
	 * @return array
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function feed_settings_fields() {

		$campaigns        = LR_Campaign::get_campaigns();
		$campaigns_parsed = array(
			array(
				'label' => esc_html__( 'Select campaign', 'leads-rocks' ),
				'value' => -1
			)
		);

		// Parse campaign list.
		foreach ( $campaigns as $campaign ) {
			$campaigns_parsed[] = array(
				'label' => esc_html( $campaign->name ),
				'value' => esc_attr( $campaign->_id )
			);
		}

		// Basic panel structure.
		$panel_data = array(
			array(
				'title'  => esc_html__( 'Leads Rocks! Integration', 'leads-rocks' ),
				'fields' => array(
					array(
						'label' => esc_html__( 'Campaign Name', 'leads-rocks' ),
						'type'  => 'hidden',
						'name'  => 'campaign_name'
					),
					array(
						'label'    => esc_html__( 'Campaign', 'leads-rocks' ),
						'type'     => 'select',
						'name'     => 'campaign',
						'required' => true,
						'tooltip'  => esc_html__( 'Select the desired campaign.', 'leads-rocks' ),
						'choices'  => $campaigns_parsed,
						'onchange' => 'jQuery( "input#campaign_name" ).val( jQuery( "#campaign option:selected" ).text() );'
					)
				)
			)
		);

		// Find the selected campaign.
		$current_feed = $this->get_feed( $this->get_current_feed_id() );

		if ( $current_feed !== false ) {

			$current_campaign = LR_Campaign::get_campaign_data( $current_feed[ 'meta' ][ 'campaign' ] );

			// Parse fields.
			$fields = array();
			foreach ( $current_campaign->fields as $field ) {
				$fields[] = array(
					'label' => esc_html( $field->title ),
					'name'  => esc_attr( $field->field )
				);
			}

			$panel_data[ 0 ][ 'fields' ][] = array(
				'label'     => esc_html__( 'Field Mapping', 'leads-rocks' ),
				'type'      => 'field_map',
				'name'      => 'campaign_mapping',
				'field_map' => $fields
			);

		}

		return $panel_data;

	}

	/**
	 * What fields to display in feed list.
	 *
	 * @return array
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function feed_list_columns() {
		return array(
			'campaign_name' => esc_html__( 'Campaign Name', 'leads-rocks-wordress' )
		);
	}

	/**
	 * Process the form and send to Leads Rocks!
	 *
	 * @param $feed
	 * @param $entry
	 * @param $form
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function process_feed( $feed, $entry, $form ) {

		// Get campaign id.
		$campaign = $feed[ 'meta' ][ 'campaign' ];

		// Get mapped fields.
		$field_mapping = $this->get_field_map_fields( $feed, 'campaign_mapping' );
		$fields        = array();

		foreach ( $field_mapping as $field => $field_id ) {
			$fields[ $field ] = $this->get_field_value( $form, $entry, $field_id );
		}

		// Submit the lead.
		new LR_Lead( $campaign, $fields );

	}

}