<?php
/**
 * Login Me Now Pro Admin Ajax Base.
 *
 * @package Login Me Now
 * @since 2.0.0
 * @version 2.0.0
 */

namespace Login_Me_Now_Pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Admin_Ajax.
 *
 * @since 2.0.0
 */
class Admin_Ajax {

	/**
	 * Instance
	 *
	 * @access private
	 * @var null $instance
	 * @since 2.0.0
	 */
	private static $instance;

	/**
	 * Initiator
	 *
	 * @since 2.0.0
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
	 * Constructor
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_filter( 'login_me_now_admin_settings_datatypes', array( $this, 'login_me_now_admin_settings_datatypes' ) );
	}

	/**
	 * Return boolean settings for admin dashboard app.
	 *
	 * @return array
	 * @since 2.0.0
	 */
	public function login_me_now_admin_settings_datatypes( $args ) {
		$default = array(
			'lmn_pro_lic'                  => 'string',

			'google_pro_exclude_pages'     => 'array',
			'google_pro_default_user_role' => 'string',
			'google_pro_redirect_url'      => 'string',
		);

		$args = wp_parse_args( $args, $default );

		return $args;
	}
}

Admin_Ajax::get_instance();