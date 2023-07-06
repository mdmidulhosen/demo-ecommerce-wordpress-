<?php
/**
 * @author  HeyMehedi
 * @since   0.90
 * @version 0.96
 */

namespace Login_Me_Now;

use Exception;
use WP_Error;
use WP_REST_Request;
use WP_User;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

/**
 * The JWT Handling Class
 */
class JWT_Auth {

	/**
	 * Supported algorithms to sign the token.
	 *
	 * @var array|string[]
	 * @since 0.9
	 * @see https://www.rfc-editor.org/rfc/rfc7518#section-3
	 */
	private $supported_algorithms = array( 'HS256', 'HS384', 'HS512', 'RS256', 'RS384', 'RS512', 'ES256', 'ES384', 'ES512', 'PS256', 'PS384', 'PS512' );

	/**
	 * Get the user and password in the request body and generate a JWT
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return mixed|WP_Error|null
	 */
	public function generate_token( WP_REST_Request $request ) {
		$username   = $request->get_param( 'username' );
		$password   = $request->get_param( 'password' );
		$expiration = $request->get_param( 'expiration' );

		/** Try to authenticate the user with the passed credentials*/
		$user = wp_authenticate( $username, $password );

		/** If the authentication fails return an error*/
		if ( is_wp_error( $user ) ) {
			$error_code = $user->get_error_code();

			return new WP_Error(
				'[login_me_now] ' . $error_code,
				$user->get_error_message( $error_code ),
				array(
					'status' => 403,
				)
			);
		}

		return $this->new_token( $user, $expiration );
	}

	/**
	 * Random number generate
	 *
	 * @return Integer
	 */
	private function rand_number() {
		$number = mt_rand( 1000000000000000, 9999999999999999 );

		return $number;
	}

	/**
	 * Get the token in the request body and generate a JWT
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return mixed|WP_Error|null
	 */
	public function regenerate_token( WP_REST_Request $request ) {
		$token    = $request->get_param( 'token' );
		$username = $password = null;

		return 'have to work on it later';

		/** Try to authenticate the user with the passed credentials*/
		$user = wp_authenticate( $username, $password );

		/** If the authentication fails return an error*/
		if ( is_wp_error( $user ) ) {
			$error_code = $user->get_error_code();

			return new WP_Error(
				'[login_me_now] ' . $error_code,
				$user->get_error_message( $error_code ),
				array(
					'status' => 403,
				)
			);
		}

		return $this->new_token( $user );
	}

	/**
	 * Get the shareable login link
	 *
	 * @param Integer $user_id
	 * @param Integer $expiration
	 *
	 * @return string|WP_Error|null
	 */
	public function get_shareable_link( Int $user_id, Int $expiration = 7 ) {
		$user = get_userdata( $user_id );
		if ( ! $user ) {
			return;
		}

		$token = $this->new_token( $user, $expiration, false );
		if ( ! $token ) {
			return;
		}

		$link = sprintf( '%s%s', site_url( '/?login-me-now&token=' ), $token );

		return $link;
	}

	/**
	 * Generate a JWT
	 *
	 * @param WP_User $user
	 * @param Integer $expiration
	 * @param bool $additional_data
	 *
	 * @return mixed|WP_Error|null
	 */
	public function new_token( WP_User $user, Int $expiration = 7, $additional_data = true ) {
		$secret_key = self::get_secret_key();

		/** First thing, check the secret key if not exist return an error*/
		if ( ! $secret_key ) {
			return new WP_Error(
				'login_me_now_bad_config',
				__( 'JWT is not configured properly, please contact the admin', 'login-me-now' ),
				array(
					'status' => 403,
				)
			);
		}

		/** Valid credentials, the user exists create the according Token */
		$issuedAt  = time();
		$notBefore = apply_filters( 'login_me_now_not_before', $issuedAt, $issuedAt );
		$expire    = apply_filters( 'login_me_now_expire', $issuedAt + ( DAY_IN_SECONDS * $expiration ), $issuedAt );

		$rand_number = (Int) $this->rand_number();

		$token = array(
			'iss'  => get_bloginfo( 'url' ),
			'iat'  => $issuedAt,
			'nbf'  => $notBefore,
			'exp'  => $expire,
			'data' => array(
				'user' => array(
					'id' => $user->data->ID,
				),
				'tid'  => $rand_number,
			),
		);

		/** Let the user modify the token data before the sign. */
		$algorithm = $this->get_algorithm();

		if ( $algorithm === false ) {
			return new WP_Error(
				'login_me_now_unsupported_algorithm',
				__( 'Algorithm not supported, see https://www.rfc-editor.org/rfc/rfc7518#section-3', 'login-me-now' ),
				array(
					'status' => 403,
				)
			);
		}

		$token = JWT::encode(
			apply_filters( 'login_me_now_token_before_sign', $token, $user ),
			$secret_key,
			$algorithm
		);

		/** Store the token ref in user meta using the $issuedAt, so we can block the token if needed */
		Tokens_Table::insert( $user->data->ID, $rand_number, $expire, 'active' );

		( new Logs_DB )->insert( $user->data->ID, "Generated an extension token" );

		if ( ! $additional_data ) {
			return $token;
		}

		/** The token is signed, now create the object with no sensible user data to the client*/
		$data = array(
			'token'             => $token,
			'site_url'          => get_bloginfo( 'url' ),
			'site_icon_url'     => get_site_icon_url( 'url' ),
			'user_email'        => $user->data->user_email,
			'user_nicename'     => $user->data->user_nicename,
			'user_display_name' => $user->data->display_name,
		);

		/** Let the user modify the data before send it back */

		return apply_filters( 'login_me_now_token_before_dispatch', $data, $user );
	}

	/**
	 * Main validation function
	 *
	 * This function is used by the /token/validate endpoint and
	 * by our middleware.
	 *
	 * The function take the token and try to decode it and validated it.
	 *
	 * @param WP_REST_Request $request
	 * @param bool|string $token
	 * @param string $return_type | token, data, user | default data
	 *
	 * @return WP_Error | Object | Array
	 */
	public function validate_token( WP_REST_Request $request, $return_type = 'data' ) {
		$req_token = $request->get_param( 'token' );

		/**
		 * if the format is not valid return an error.
		 */
		if ( ! $req_token ) {
			return new WP_Error(
				'login_me_now_invalid_token',
				'Invalid token.',
				array(
					'status' => 403,
				)
			);
		}

		/** Get the Secret Key */
		$secret_key = self::get_secret_key();
		if ( ! $secret_key ) {
			return new WP_Error(
				'login_me_now_bad_config',
				'JWT is not configured properly, please contact the admin',
				array(
					'status' => 403,
				)
			);
		}

		/** Try to decode the token */
		try {
			$algorithm = $this->get_algorithm();
			if ( $algorithm === false ) {
				return new WP_Error(
					'login_me_now_unsupported_algorithm',
					__( 'Algorithm not supported, see https://www.rfc-editor.org/rfc/rfc7518#section-3', 'login-me-now' ),
					array(
						'status' => 403,
					)
				);
			}

			$token = JWT::decode( $req_token, new Key( $secret_key, $algorithm ) );

			/** The Token is decoded now validate the iss */
			if ( $token->iss !== get_bloginfo( 'url' ) ) {
				/** The iss do not match, return error */
				return new WP_Error(
					'login_me_now_bad_iss',
					'The iss do not match with this server',
					array(
						'status' => 403,
					)
				);
			}

			/** So far so good, validate the user id in the token */
			if ( ! isset( $token->data->user->id ) ) {
				/** No user id in the token, abort!! */
				return new WP_Error(
					'login_me_now_bad_request',
					'User ID not found in the token',
					array(
						'status' => 403,
					)
				);
			}

			$token_id     = ! empty( $token->data->tid ) ? $token->data->tid : false;
			$token_status = Tokens_Table::get_token_status( $token_id );
			if ( ! $token_status || 'active' != $token_status ) {
				return $token_status;
			}

			/** Everything looks good return the decoded token if we are using the token */
			if ( 'token' === $return_type ) {
				return $req_token;
			}

			$user = get_userdata( $token->data->user->id );

			if ( 'user_id' === $return_type ) {
				return (Int) $token->data->user->id;
			}

			/** The token already signed, now create the object with no sensible user data to the client*/
			$data = array(
				'token'             => $req_token,
				'site_url'          => get_bloginfo( 'url' ),
				'site_icon_url'     => get_site_icon_url( 'url' ),
				'user_email'        => $user->data->user_email,
				'user_nicename'     => $user->data->user_nicename,
				'user_display_name' => $user->data->display_name,
			);

			/** This is for the /validate endpoint*/

			return $data;
		} catch ( Exception $e ) {
			/** Something were wrong trying to decode the token, send back the error */
			return new WP_Error(
				'login_me_now_invalid_token',
				$e->getMessage(),
				array(
					'status' => 403,
				)
			);
		}
	}

	/**
	 * Get the algorithm used to sign the token via the filter login_me_now_algorithm.
	 * and validate that the algorithm is in the supported list. if not exist then add new
	 *
	 * @return false|mixed|null
	 */
	public function get_algorithm() {
		$algo = get_option( 'login_me_now_algorithm' );
		if ( ! $algo ) {
			$algo = 'HS256';
			update_option( 'login_me_now_algorithm', $algo );
		}

		$algorithm = apply_filters( 'login_me_now_algorithm', $algo );
		if ( ! in_array( $algorithm, $this->supported_algorithms ) ) {
			return false;
		}

		return $algorithm;
	}

	/**
	 * Get the secret key,
	 * if not exists then generate and save to option
	 *
	 * @return string
	 */
	public static function get_secret_key() {
		$key = get_option( 'login_me_now_secret_key' );

		if ( ! $key ) {
			$key = Helper::generate_key();
			update_option( 'login_me_now_secret_key', $key );
		}

		return $key;
	}
}