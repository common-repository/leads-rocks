<?php
// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class LR_Auth
 *
 * @since  1.0.0
 * @author Dor Zuberi <me@dorzki.co.il>
 */
class LR_Auth {

	/**
	 * Authentication token.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private $auth_token = null;

	/**
	 * Account username.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private $username;

	/**
	 * Account password.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private $password;


	/**
	 * LR_Auth constructor.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function __construct() {

		$options = get_option( 'leadsrocks_options' );

		$this->username = ( isset( $options[ 'leads-rocks_auth_username' ] ) ) ? $options[ 'leads-rocks_auth_username' ] : '';
		$this->password = ( isset( $options[ 'leads-rocks_auth_password' ] ) ) ? $options[ 'leads-rocks_auth_password' ] : '';

		$this->auth_token = get_transient( 'leads-rocks_token' );

	}

	/**
	 * Retrieve authentication token.
	 *
	 * @return string|void
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	public function get_token() {

		if ( empty( $this->auth_token ) ) {
			$this->initiate_login();
		}

		return $this->auth_token;

	}

	/**
	 * Get authorization token using username and password.
	 *
	 * @since  1.0.0
	 * @author Dor Zuberi <me@dorzki.co.il>
	 */
	private function initiate_login() {

		if ( empty( $this->username ) || empty( $this->password ) ) {
			return;
		}

		$url      = LR_API_URL . "/login";
		$response = wp_remote_request( $url, array(
			'method'    => 'POST',
			'sslverify' => false,
			'headers'   => array(
				'Content-type' => 'application/json'
			),
			'body'      => json_encode( array(
				'username' => $this->username,
				'password' => sha1( $this->password )
			) )
		) );

		if ( is_wp_error( $response ) ) {
			return;
		} else {

			$response[ 'body' ] = json_decode( $response[ 'body' ] );
			set_transient( 'leads-rocks_token', $response[ 'body' ]->token, 7 * DAY_IN_SECONDS );

			$this->auth_token = $response[ 'body' ]->token;

		}

	}

}