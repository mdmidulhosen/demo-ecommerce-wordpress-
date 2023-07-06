<?php
/**
 * Login Me Now Pro Admin Loader
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
 * Login_Me_Now_Admin_Loader
 *
 * @since 2.0.0
 */
class Login_Me_Now_Admin_Loader {

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
		define( 'LOGIN_ME_NOW_PRO_ADMIN_DIR', LOGIN_ME_NOW_PRO_BASE_DIR . 'admin/' );
		define( 'LOGIN_ME_NOW_PRO_ADMIN_URL', LOGIN_ME_NOW_PRO_BASE_URL . 'admin/' );

		$this->includes();
	}

	/**
	 * Include required classes.
	 *
	 * @since 2.0.0
	 */
	public function includes() {
		require_once LOGIN_ME_NOW_PRO_ADMIN_DIR . 'includes/functions.php';
		require_once LOGIN_ME_NOW_PRO_ADMIN_DIR . 'includes/class-updater.php';
		require_once LOGIN_ME_NOW_PRO_ADMIN_DIR . 'includes/class-admin-ajax.php';
	}
}

Login_Me_Now_Admin_Loader::get_instance();