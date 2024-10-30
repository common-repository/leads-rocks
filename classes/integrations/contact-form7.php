<?php
// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class LR_ContactForm7
 *
 * @since  1.0.0
 * @author Dor Zuberi <me@dorzki.co.il>
 */
class LR_ContactForm7 {

	/**
	 * LR_ContactForm7 constructor.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function __construct() {

		add_action( 'init', array( &$this, 'integration_init' ) );

	}

	/**
	 * Add integration control panel.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function integration_init() {

		if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
			return;
		}

		add_filter( 'wpcf7_editor_panels', array( &$this, 'add_integration_panel' ) );
		add_action( 'wpcf7_after_save', array( &$this, 'save_integration_data' ) );
		add_action( 'wpcf7_before_send_mail', array( &$this, 'send_lead' ) );

	}

	/**
	 * Add integration panel tab.
	 *
	 * @param $panels
	 *
	 * @return array|void
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function add_integration_panel( $panels ) {

		if ( ! is_array( $panels ) ) {
			return;
		}

		$panels[ 'leadsrocks' ] = array(
			'title'    => esc_html__( 'Leads Rocks!', 'leads-rocks' ),
			'callback' => array( &$this, 'integration_panel_html' )
		);

		return $panels;

	}

	/**
	 * Prints integration panel HTML.
	 *
	 * @param $form
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function integration_panel_html( $form ) {

		$campaigns        = LR_Campaign::get_campaigns();
		$settings         = get_post_meta( $form->id(), 'leadsrocks_data', true );
		$campaign_id      = ( isset( $settings[ 'campaign' ] ) ) ? $settings[ 'campaign' ] : '';
		$current_campaign = null;
		$tags             = $form->collect_mail_tags();

		// If there are no campaigns.
		if ( empty( $campaigns ) ) {
			$campaigns = array();
		}

		// Find the selected campaign.
		$current_campaign = LR_Campaign::get_campaign_data( $campaign_id );

		// Build fields list & values.
		if ( ! is_null( $current_campaign ) ) {
			foreach ( $current_campaign->fields as $field ) {
				$fields[ $field->field ] = ( isset( $settings[ 'fields' ][ $field->field ] ) ) ? $settings[ 'fields' ][ $field->field ] : '';
			}
		}

		include_once( LR_PLUGIN_DIR . '/templates/contact-form7-panel.php' );

	}

	/**
	 * Saves integration data.
	 *
	 * @param $form
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function save_integration_data( $form ) {

		update_post_meta( $form->id(), 'leadsrocks_data', array(
			'campaign' => $_POST[ 'leads-rocks-campaign' ],
			'fields'   => $_POST[ 'leads-rocks-fields' ]
		) );

	}

	/**
	 * Process the form and send to Leads Rocks!
	 *
	 * @param $form
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function send_lead( $form ) {

		$settings = get_post_meta( $form->id(), 'leadsrocks_data', true );

		// Integration on?
		if ( empty( $settings ) || ! isset( $settings[ 'campaign' ] ) ) {
			return;
		}

		$cf7  = WPCF7_Submission::get_instance();
		$data = $cf7->get_posted_data();

		$campaign = $settings[ 'campaign' ];
		$fields   = array();

		foreach ( $settings[ 'fields' ] as $field => $tag ) {
			$fields[ $field ] = $data[ $tag ];
		}

		new LR_Lead( $campaign, $fields );

	}

}