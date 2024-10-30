<?php
// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class LR_Campaigns
 *
 * @since  1.0.0
 * @author Dor Zuberi <me@dorzki.co.il>
 */
class LR_Campaign {

	/**
	 * An instance of LR_Auth.
	 *
	 * @var LR_Auth
	 * @since 1.0.0
	 */
	private static $auth;

	/**
	 * A list of campaigns.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected static $campaigns = null;


	/**
	 * LR_Campaigns constructor.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function __construct() {

		$this->init();

	}

	/**
	 * Initiate the class.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	private function init() {

		$this->auth = new LR_Auth;

	}

	/**
	 * Retrieves user campaigns.
	 *
	 * @return array|void
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public static function get_campaigns() {

		if ( is_null( self::$campaigns ) ) {

			self::$auth = new LR_Auth;
			$token      = self::$auth->get_token();

			$url      = LR_API_URL . "/campaign/all";
			$response = wp_remote_request( $url, array(
				'method'    => 'GET',
				'sslverify' => false,
				'headers'   => array(
					'Authorization' => 'Bearer ' . $token,
					'Content-type'  => 'application/json'
				)
			) );

			if ( is_wp_error( $response ) ) {
				return;
			} else {

				$response[ 'body' ] = json_decode( $response[ 'body' ] );
				self::$campaigns    = $response[ 'body' ]->campaigns;

			}

		}

		return self::$campaigns;

	}

	/**
	 * Retrieve specific campaign data by id.
	 *
	 * @param $campaign_id
	 *
	 * @return mixed|void
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public static function get_campaign_data( $campaign_id ) {

		if ( empty( $campaign_id ) ) {
			return;
		}

		if ( is_null( self::$campaigns ) ) {
			self::get_campaigns();
		}

		// Get the campaign
		foreach ( self::$campaigns as $campaign ) {
			if ( $campaign->_id === $campaign_id ) {
				return $campaign;
			}
		}

	}

}