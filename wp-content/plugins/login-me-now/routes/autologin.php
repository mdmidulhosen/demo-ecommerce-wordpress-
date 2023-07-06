<?php
/**
 * @author  HeyMehedi
 * @since   0.90
 * @version 0.90
 */

namespace Login_Me_Now\Routes;

defined( 'ABSPATH' ) || exit;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Login_Me_Now\AutoLogin;
use Login_Me_Now\JWT_Auth;
use Login_Me_Now\Routes\Rest_Base;
use WP_REST_Request;

/**
 * Auto_Login API class.
 */
class API_AutoLogin extends Rest_Base {

	/**
	 * Registers the route for Auto Login.
	 *
	 * @since 4.7.0
	 *
	 * @see register_rest_route()
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/autologin',
			array(
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'verify' ),
					'permission_callback' => '__return_true',
				),
			)
		);
	}

	public function verify( WP_REST_Request $request ) {
		if ( empty( $request['token'] ) ) {
			return __( 'No Token Provided', 'login-me-now' );
		}

		/** First thing, check the secret key if not exist return an error*/
		$secret_key = JWT_Auth::get_secret_key();
		if ( ! $secret_key ) {
			return __( 'JWT is not configured properly, please contact the admin', 'login-me-now' );
		}

		try {
			$algorithm = ( new JWT_Auth )->get_algorithm();
			$payload   = JWT::decode( $request['token'], new Key( $secret_key, $algorithm ) );
		} catch ( \Throwable $th ) {
			return $th->getMessage();
		}

		$user_id = ! empty( $payload->data->user->id ) ? $payload->data->user->id : false;

		if ( ! $user_id ) {
			return __( 'User not found', 'login-me-now' );
		}

		( new AutoLogin )->now( $user_id );
	}
}

new API_AutoLogin;