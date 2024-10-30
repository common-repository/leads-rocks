<?php
// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class LR_PojoForms
 *
 * @since  1.0.0
 * @author Dor Zuberi <me@dorzki.co.il>
 */
class LR_PojoForms {

	/**
	 * @var string
	 * @since 1.0.0
	 */
	public $field_prefix = 'leadsrocks_';


	/**
	 * LR_PojoForms constructor.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'integration_init' ) );

	}

	/**
	 * Register integration.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function integration_init() {

		if ( ! class_exists( 'Pojo_MetaBox' ) ) {
			return;
		}

		add_filter( 'pojo_meta_boxes', array( $this, 'add_integration_panel' ), 35 );
		add_action( 'pojo_forms_mail_sent', array( $this, 'send_lead' ), 10, 2 );

	}

	/**
	 * Prints integration panel HTML.
	 *
	 * @param array $metaboxes
	 *
	 * @return array
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function add_integration_panel( $metaboxes = array() ) {

		$form_id = 0;

		if ( isset( $_GET[ 'post' ] ) ) {
			$form_id = (int) $_GET[ 'post' ];
		} elseif ( isset( $_POST[ 'post_ID' ] ) ) {
			$form_id = (int) $_POST[ 'post_ID' ];
		}

		$metabox_fields = array();
		$campaigns      = LR_Campaign::get_campaigns();
		$campaign_id    = get_post_meta( $form_id, $this->field_prefix . 'campaign', true );

		// Campaign field
		$metabox_fields[] = array(
			'id'      => 'campaign',
			'title'   => esc_html__( 'Campaign', 'leads-rocks' ),
			'type'    => Pojo_MetaBox::FIELD_SELECT,
			'options' => array(
				'-1' => esc_html__( 'Select campaign', 'leads-rocks' ),
			)
		);

		foreach ( $campaigns as $campaign ) {
			$metabox_fields[ 0 ][ 'options' ][ $campaign->_id ] = $campaign->name;
		}


		// Campaign fields
		if ( ! empty( $campaign_id ) ) {

			$current_campaign = LR_Campaign::get_campaign_data( $campaign_id );
			$tags             = array( -1 => esc_html__( 'Select tag', 'leads-rocks' ) ) + $this->get_tag_list( $form_id );

			$metabox_fields[] = array(
				'id'    => 'field_mapping_heading',
				'title' => esc_html__( 'Field Mapping', 'leads-rocks' ),
				'type'  => Pojo_MetaBox::FIELD_HEADING
			);

			foreach ( $current_campaign->fields as $field ) {

				$metabox_fields[] = array(
					'id'      => $field->field,
					'title'   => $field->title,
					'type'    => Pojo_MetaBox::FIELD_SELECT,
					'options' => $tags
				);

			}

		}

		$metaboxes[] = array(
			'id'         => 'leads-rocks',
			'title'      => esc_html__( 'Leads Rocks!', 'leads-rocks' ),
			'post_types' => array( 'pojo_forms' ),
			'context'    => 'normal',
			'prefix'     => $this->field_prefix,
			'fields'     => $metabox_fields
		);

		return $metaboxes;

	}

	/**
	 * Retrieves the form tags list.
	 *
	 * @param string $form_id
	 *
	 * @return array|void
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	private function get_tag_list( $form_id = '' ) {

		if ( empty( $form_id ) ) {
			return;
		}

		$form_id    = (int) $form_id;
		$tags       = array();
		$total_tags = (int) get_post_meta( $form_id, 'form_fields', true );

		// Retrieve tags from database.
		for ( $i = 0; $i < $total_tags; $i++ ) {

			$tag_id    = (int) get_post_meta( $form_id, 'form_fields[' . $i . '][field_id]', true );
			$tag_label = get_post_meta( $form_id, 'form_fields[' . $i . '][name]', true );

			$tags[ $tag_id ] = $tag_label;

		}

		return $tags;

	}

	/**
	 * Process the form and send to Leads Rocks!
	 *
	 * @param $form_id
	 * @param $form_data
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function send_lead( $form_id, $form_data ) {

		// Does the form linked to Leads Rocks?
		$campaign_id = get_post_meta( $form_id, $this->field_prefix . 'campaign', true );

		if ( ! empty( $campaign_id ) ) {

			$current_campaign = LR_Campaign::get_campaign_data( $campaign_id );
			$form_fields      = $this->get_tag_list( $form_id );
			$fields           = array();

			// Build fields array.
			foreach ( $current_campaign->fields as $field ) {

				$the_field               = (int) get_post_meta( $form_id, $this->field_prefix . $field->field, true );
				$field_label             = $form_fields[ $the_field ];
				$field_pos               = array_search( $field_label, array_column( $form_data, 'title' ) );
				$fields[ $field->field ] = ( $field_pos !== false ) ? $form_data[ $field_pos ][ 'value' ] : null;

			}

			new LR_Lead( $campaign_id, $fields );

		}

	}

}