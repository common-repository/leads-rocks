<?php
// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class LR_Lead
 *
 * @since  1.0.0
 * @author Dor Zuberi
 */
class LR_Lead {

	/**
	 * LR_Lead constructor.
	 *
	 * @param $campaign
	 * @param $fields
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function __construct( $campaign, $fields ) {

		if ( empty( $campaign ) || empty( $fields ) ) {
			return;
		}

		if ( ! preg_match( '/^[a-f\d]{24}$/i', $campaign ) ) {
			return;
		}

		$this->create( $campaign, $fields );

	}

	/**
	 * Retrieves current user utm parameters.
	 *
	 * @return array
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	private function get_utm_params() {

		$utm_fields = array( 'utm_campaign', 'utm_content', 'utm_term', 'utm_medium', 'utm_source' );
		$utms       = array();

		foreach ( $utm_fields as $utm_field ) {

			if ( isset( $_SESSION[ $utm_field ] ) ) {

				$utm_label          = str_replace( 'utm_', '', $utm_field );
				$utms[ $utm_label ] = $_SESSION[ $utm_field ];

			}

		}

		return $utms;

	}

	/**
	 * Create a new lead.
	 *
	 * @param $campaign
	 * @param $fields
	 *
	 * @return bool
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	private function create( $campaign, $fields ) {

		$url      = LR_API_URL . '/public/lead';
		$response = wp_remote_request( $url, array(
			'method'    => 'PUT',
			'sslverify' => false,
			'headers'   => array(
				'Content-type' => 'application/json'
			),
			'body'      => json_encode( array(
				'campaign_id' => $campaign,
				'fields'      => $fields,
				'utm'         => $this->get_utm_params()
			) )
		) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		return true;

	}

}