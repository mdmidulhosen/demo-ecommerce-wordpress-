<?php
/**
 * Login Me Now Admin Ajax Base.
 *
 * @package Login Me Now
 * @since 0.95
 * @version 0.98
 */

namespace Login_Me_Now;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Admin_Ajax.
 *
 * @since 0.94
 */
class Admin_Ajax {

	/**
	 * Ajax action prefix.
	 *
	 * @var string
	 * @since 0.94
	 */
	private $prefix = 'login-me-now';

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
			/** @psalm-suppress InvalidPropertyAssignmentValue */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
			self::$instance = new self();
			/** @psalm-suppress InvalidPropertyAssignmentValue */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		}

		return self::$instance;
	}

	/**
	 * Errors class instance.
	 *
	 * @var array
	 * @since 0.94
	 */
	private $errors = array();

	/**
	 * Constructor
	 *
	 * @since 0.94
	 */
	public function __construct() {
		$this->errors = array(
			'permission' => __( 'Sorry, you are not allowed to do this operation.', 'login-me-now' ),
			'nonce'      => __( 'Nonce validation failed', 'login-me-now' ),
			'default'    => __( 'Sorry, something went wrong.', 'login-me-now' ),
			'invalid'    => __( 'No post data found!', 'login-me-now' ),
		);

		add_action( 'wp_ajax_login_me_now_update_admin_setting', array( $this, 'login_me_now_update_admin_setting' ) );
		add_action( 'wp_ajax_login_me_now_recommended_plugin_activate', array( $this, 'required_plugin_activate' ) );

		add_action( 'wp_ajax_login_me_now_generate_onetime_link', array( $this, 'login_me_now_generate_onetime_link' ) );
		add_action( 'wp_ajax_login_me_now_generate_extension_token', array( $this, 'login_me_now_generate_extension_token' ) );

		add_action( 'wp_ajax_update_status_of_token', array( $this, 'update_status_of_token' ) );
	}

	/**
	 * Return boolean settings for admin dashboard app.
	 *
	 * @return array
	 * @since 0.94
	 */
	public function login_me_now_admin_settings_typewise() {
		return apply_filters(
			'login_me_now_admin_settings_datatypes',
			array(
				'self_hosted_gfonts'               => 'bool',
				'preload_local_fonts'              => 'bool',

				'logs'                             => 'bool',
				'logs_expiration'                  => 'integer',

				'onetime_links'                    => 'bool',
				'onetime_links_expiration'         => 'integer',

				'reusable_links'                   => 'bool',
				'reusable_links_expiration'        => 'integer',

				'user_switching'                   => 'bool',

				'google_login'                     => 'bool',
				'google_client_id'                 => 'string',
				'google_native_login'              => 'bool',
				'google_auto_sign_in'              => 'bool',
				'google_update_existing_user_data' => 'bool',
				'google_cancel_on_tap_outside'     => 'bool',
			)
		);
	}

	/**
	 * Save settings.
	 *
	 * @return void
	 * @since 0.94
	 */
	public function login_me_now_update_admin_setting() {

		$response_data = array( 'message' => $this->get_error_msg( 'permission' ) );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( $response_data );
		}

		if ( empty( $_POST ) ) {
			$response_data = array( 'message' => $this->get_error_msg( 'invalid' ) );
			wp_send_json_error( $response_data );
		}

		/**
		 * Nonce verification.
		 */
		if ( ! check_ajax_referer( 'login_me_now_update_admin_setting', 'security', false ) ) {
			$response_data = array( 'message' => $this->get_error_msg( 'nonce' ) );
			wp_send_json_error( $response_data );
		}

		$get_bool_settings = $this->login_me_now_admin_settings_typewise();
		/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$sub_option_key = isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';
		/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$sub_option_value = '';

		// @codingStandardsIgnoreStart
		if ( isset( $get_bool_settings[$sub_option_key] ) ) {
			if ( 'bool' === $get_bool_settings[$sub_option_key] ) {
				/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$val = isset( $_POST['value'] ) && 'true' === sanitize_text_field( $_POST['value'] ) ? true : false;
				/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$sub_option_value = $val;
			} else {
				/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$val = isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : '';
				/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$sub_option_value = $val;
			}
		}
		// @codingStandardsIgnoreEnd

		API_Init::update_admin_settings_option( $sub_option_key, $sub_option_value );

		$response_data = array(
			'message' => __( 'Successfully saved data!', 'login-me-now' ),
		);

		wp_send_json_success( $response_data );
	}

	/**
	 * Get ajax error message.
	 *
	 * @param string $type Message type.
	 * @return string
	 * @since 0.94
	 */
	public function get_error_msg( $type ) {

		if ( ! isset( $this->errors[$type] ) ) {
			$type = 'default';
		}

		return $this->errors[$type];
	}

	/**
	 * Required Plugin Activate
	 *
	 * @since 0.94
	 */
	public function required_plugin_activate() {

		$response_data = array( 'message' => $this->get_error_msg( 'permission' ) );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( $response_data );
		}

		if ( empty( $_POST ) ) {
			$response_data = array( 'message' => $this->get_error_msg( 'invalid' ) );
			wp_send_json_error( $response_data );
		}

		/**
		 * Nonce verification.
		 */
		if ( ! check_ajax_referer( 'login_me_now_plugin_manager_nonce', 'security', false ) ) {
			$response_data = array( 'message' => $this->get_error_msg( 'nonce' ) );
			wp_send_json_error( $response_data );
		}

		/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		if ( ! current_user_can( 'install_plugins' ) || ! isset( $_POST['init'] ) || ! sanitize_text_field( wp_unslash( $_POST['init'] ) ) ) {
			/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
			wp_send_json_error(
				array(
					'success' => false,
					'message' => __( 'No plugin specified', 'login-me-now' ),
				)
			);
		}

		/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$plugin_init = ( isset( $_POST['init'] ) ) ? sanitize_text_field( wp_unslash( $_POST['init'] ) ) : '';
		/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort

		$activate = activate_plugin( $plugin_init, '', false, true );

		if ( is_wp_error( $activate ) ) {
			/** @psalm-suppress PossiblyNullReference */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
			wp_send_json_error(
				array(
					'success' => false,
					'message' => $activate->get_error_message(),
				)
			);
			/** @psalm-suppress PossiblyNullReference */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		}

		wp_send_json_success(
			array(
				'success' => true,
				'message' => __( 'Plugin Successfully Activated', 'login-me-now' ),
			)
		);
	}

	/**
	 * Generate Onetime Token
	 *
	 * @since 0.94
	 */
	public function login_me_now_generate_onetime_link() {

		$response_data = array( 'message' => $this->get_error_msg( 'permission' ) );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( $response_data );
		}

		if ( empty( $_POST ) ) {
			$response_data = array( 'message' => $this->get_error_msg( 'invalid' ) );
			wp_send_json_error( $response_data );
		}

		/**
		 * Nonce verification.
		 */
		if ( ! check_ajax_referer( 'login_me_now_generate_onetime_link_nonce', 'security', false ) ) {
			$response_data = array( 'message' => $this->get_error_msg( 'nonce' ) );
			wp_send_json_error( $response_data );
		}

		/**
		 * Generate the token.
		 */
		$user_id    = get_current_user_id();
		$expiration = (Int) lmn_get_option( 'onetime_links_expiration', 8 );
		$onetime    = ( new Onetime_Number() )->get_shareable_link( $user_id, $expiration );

		if ( is_wp_error( $onetime ) ) {
			wp_send_json_error(
				array(
					'success' => false,
					'message' => $onetime->get_error_message(),
				)
			);
		}

		( new Logs_DB )->insert( $user_id, __( "Generated an onetime link", "login-me-now" ) );

		wp_send_json_success(
			array(
				'success' => true,
				'message' => __( 'Onetime Link Successfully Generated', 'login-me-now' ),
				'text'    => $onetime['number'],
				'link'    => $onetime['link'],

			)
		);
	}

	/**
	 * Generate Extension Token
	 *
	 * @since 0.96
	 */
	public function login_me_now_generate_extension_token() {

		$response_data = array( 'message' => $this->get_error_msg( 'permission' ) );

		// if ( ! current_user_can( 'manage_options' ) ) {
		// 	wp_send_json_error( $response_data );
		// }

		if ( empty( $_POST ) ) {
			$response_data = array( 'message' => $this->get_error_msg( 'invalid' ) );
			wp_send_json_error( $response_data );
		}

		/**
		 * Nonce verification.
		 */
		if ( ! check_ajax_referer( 'login_me_now_generate_onetime_link_nonce', 'security', false ) ) {
			$response_data = array( 'message' => $this->get_error_msg( 'nonce' ) );
			wp_send_json_error( $response_data );
		}

		/**
		 * Generate the token.
		 */
		$expiration = (Int) lmn_get_option( 'reusable_links_expiration', 7 );
		$user_id    = get_current_user_id();
		$user       = get_userdata( $user_id );
		if ( ! $user ) {
			wp_send_json_error(
				array(
					'success' => false,
					'message' => __( 'Something wen\'t wrong', 'login-me-now' ),
				)
			);

			return;
		}

		$additional_data = false;
		if ( ! empty( $_POST['additional_data'] ) ) {
			$additional_data = true;
		}

		$token = ( new JWT_Auth() )->new_token( $user, $expiration, $additional_data );

		if ( is_wp_error( $token ) ) {
			wp_send_json_error(
				array(
					'success' => false,
					'message' => $token->get_error_message(),
				)
			);
		}

		wp_send_json_success(
			array(
				'success' => true,
				'message' => __( 'Extension Token Successfully Generated', 'login-me-now' ),
				'link'    => $token,
			)
		);
	}

	/**
	 * Update Particular Extension Token Status
	 *
	 * @since 0.96
	 */
	public function update_status_of_token() {
		$status = ! empty( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : false;
		$id     = ! empty( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : 0;

		if ( ! $status && ! $id ) {
			wp_send_json( __( "Something wen't wrong!" ) );
			wp_die();
		}

		Tokens_Table::update( $id, $status );
		wp_die();
	}
}

Admin_Ajax::get_instance();