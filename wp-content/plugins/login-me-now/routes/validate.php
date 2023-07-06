<?php
/**
 * @author  HeyMehedi
 * @since   0.90
 * @version 0.90
 */

namespace Login_Me_Now\Routes;

defined( 'ABSPATH' ) || exit;

use Login_Me_Now\JWT_Auth;
use Login_Me_Now\Routes\Rest_Base;

/**
 * Validate API class.
 */
class API_Validate extends Rest_Base {

	/**
	 * Registers the route to validate the token.
	 *
	 * @since 0.9
	 *
	 * @see register_rest_route()
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/validate',
			array(
				array(
					'methods'             => 'POST',
					'callback'            => array(  ( new JWT_Auth ), 'validate_token' ),
					'permission_callback' => '__return_true',
				),
			)
		);
	}

}

new API_Validate;