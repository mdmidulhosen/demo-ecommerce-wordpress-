<?php
/**
 * Class API_Init.
 *
 * @package Login Me Now
 * @since 0.95
 * @version 0.99
 */

namespace Login_Me_Now;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Bail if WP_REST_Controller class does not exist.
if ( ! class_exists( 'WP_REST_Controller' ) ) {
	return;
}

class API_Init extends WP_REST_Controller {

	/**
	 * Instance
	 *
	 * @access private
	 * @var null $instance
	 * @since 0.94
	 */
	private static $instance;

	/**
	 * Initiator
	 *
	 * @since 0.94
	 * @return object initialized object of class.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'login-me-now/v1';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = '/admin/settings/';

	/**
	 * Option name
	 *
	 * @access private
	 * @var string $option_name DB option name.
	 * @since 0.94
	 */
	private static $option_name = 'login_me_now_admin_settings';

	/**
	 * Admin settings dataset
	 *
	 * @access private
	 * @var array $login_me_now_admin_settings Settings array.
	 * @since 0.94
	 */
	private static $login_me_now_admin_settings = array();

	/**
	 * Constructor
	 *
	 * @since 0.94
	 */
	public function __construct() {
		self::$login_me_now_admin_settings = get_option( self::$option_name, array() );

		// REST API extensions init.
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register API routes.
	 *
	 * @since 0.94
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			$this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_admin_settings' ),
					'permission_callback' => array( $this, 'get_permissions_check' ),
					'args'                => array(),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Get common settings.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return array $updated_option defaults + set DB option data.
	 *
	 * @since 0.94
	 */
	public function get_admin_settings( $request ) {
		$db_option = get_option( 'login_me_now_admin_settings', array() );

		$defaults = apply_filters(
			'login_me_now_dashboard_rest_options',
			array(
				'self_hosted_gfonts'               => self::get_admin_settings_option( 'self_hosted_gfonts', false ),
				'preload_local_fonts'              => self::get_admin_settings_option( 'preload_local_fonts', false ),

				'logs'                             => self::get_admin_settings_option( 'logs', true ),
				'logs_expiration'                  => self::get_admin_settings_option( 'logs_expiration', 7 ),

				'onetime_links'                    => self::get_admin_settings_option( 'onetime_links', true ),
				'onetime_links_expiration'         => self::get_admin_settings_option( 'onetime_links_expiration', 8 ),

				'reusable_links'                   => self::get_admin_settings_option( 'reusable_links', true ),
				'reusable_links_expiration'        => self::get_admin_settings_option( 'reusable_links_expiration', 365 ),

				'user_switching'                   => self::get_admin_settings_option( 'user_switching', true ),

				'google_login'                     => self::get_admin_settings_option( 'google_login', false ),
				'google_client_id'                 => self::get_admin_settings_option( 'google_client_id', '' ),
				'google_native_login'              => self::get_admin_settings_option( 'google_native_login', true ),
				'google_auto_sign_in'              => self::get_admin_settings_option( 'google_auto_sign_in', false ),
				'google_update_existing_user_data' => self::get_admin_settings_option( 'google_update_existing_user_data', false ),
				'google_cancel_on_tap_outside'     => self::get_admin_settings_option( 'google_cancel_on_tap_outside', false ),

				'get_user_roles'                   => $this->get_user_roles(),
				'get_pages'                        => $this->get_pages(),
			)
		);

		$updated_option = wp_parse_args( $db_option, $defaults );

		return $updated_option;
	}

	public function get_user_roles() {
		$roles = wp_roles()->get_names();

		return $roles;
	}

	public function get_pages() {
		$return = array();
		$pages  = get_pages();

		foreach ( $pages as $key => $page ) {
			array_push(
				$return,
				array(
					'id'   => $page->ID,
					'name' => $page->post_title,
				)
			);
		}

		return $return;
	}

	/**
	 * Check whether a given request has permission to read notes.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 * @since 0.94
	 */
	public function get_permissions_check( $request ) {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return new WP_Error( 'login_me_now_rest_cannot_view', __( 'Sorry, you cannot list resources.', 'login-me-now' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Returns an value,
	 * based on the settings database option for the admin settings page.
	 *
	 * @param  string $key     The sub-option key.
	 * @param  mixed  $default Option default value if option is not available.
	 * @return mixed            Return the option value based on provided key
	 * @since 0.94
	 */
	public static function get_admin_settings_option( $key, $default = false ) {
		$value = isset( self::$login_me_now_admin_settings[$key] ) ? self::$login_me_now_admin_settings[$key] : $default;

		return $value;
	}

	/**
	 * Update an value of a key,
	 * from the settings database option for the admin settings page.
	 *
	 * @param string $key       The option key.
	 * @param mixed  $value     The value to update.
	 * @return mixed            Return the option value based on provided key
	 * @since 0.94
	 */
	public static function update_admin_settings_option( $key, $value ) {
		$settings       = get_option( self::$option_name );
		$settings[$key] = $value;
		update_option( self::$option_name, $settings );
	}
}

API_Init::get_instance();