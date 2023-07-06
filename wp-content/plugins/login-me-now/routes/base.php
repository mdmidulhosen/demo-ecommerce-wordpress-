<?php
/**
 * @author  HeyMehedi
 * @since   0.90
 * @version 0.90
 */

namespace Login_Me_Now\Routes;

defined( 'ABSPATH' ) || exit;

use \WP_REST_Controller;

class Rest_Base extends WP_REST_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'login-me-now';

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}
}
