<?php
/**
 * @author  HeyMehedi
 * @since   0.90
 * @version 0.97
 */

namespace Login_Me_Now\Routes;

defined( 'ABSPATH' ) || exit;

use Login_Me_Now\JWT_Auth;
use Login_Me_Now\Onetime_Number;
use Login_Me_Now\Routes\Rest_Base;
use WP_REST_Request;

/**
 * Generate API class.
 */
class API_Generate extends Rest_Base {

	/**
	 * Registers the route to generate the token.
	 *
	 * @since 0.90
	 *
	 * @see register_rest_route()
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/generate',
			array(
				array(
					'methods'             => 'POST',
					'callback'            => array(  ( new JWT_Auth ), 'generate_token' ),
					'permission_callback' => '__return_true',
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/generate-onetime-number',
			array(
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'generate_onetime_number' ),
					'permission_callback' => '__return_true',
				),
			)
		);
	}

	/**
	 * Generate the onetime number.
	 *
	 * @since 0.96
	 */
	public function generate_onetime_number( WP_REST_Request $request ) {
		$JWT    = new JWT_Auth;
		$result = $JWT->validate_token( $request, 'user_id' );

		if ( is_numeric( $result ) ) {
			$link = ( new Onetime_Number )->get_shareable_link( $result, 8 );
			wp_send_json_success( $link );
		}

		wp_send_json_error( array( 'status' => $result ) );
	}
}

new API_Generate;