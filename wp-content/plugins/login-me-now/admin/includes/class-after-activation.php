<?php
/**
 * @author  HeyMehedi
 * @since   0.93
 * @version 0.96
 */

namespace Login_Me_Now;

/**
 * Database related methods and actions
 *
 * @since 0.93
 */
class After_Activation {

	/**
	 * Class constructor
	 *
	 * @since 0.93
	 */
	public function __construct() {
		register_activation_hook( LOGIN_ME_NOW_BASE_DIR . 'login-me-now.php', array( $this, 'update' ) );
		$this->migrate_from_beta();
	}

	/**
	 * Create necessary tables and update options
	 *
	 * @since 0.94
	 */
	public function migrate_from_beta() {
		if ( defined( 'LOGIN_ME_NOW_VERSION' ) ) {
			$upgraded = get_option( 'login_me_now_upgraded_from_beta' );
			if ( ! $upgraded ) {
				$this->update();
				( new Tokens_Table )->alter_table();
				update_option( 'login_me_now_upgraded_from_beta', true, false );
			}
		}
	}

	public function update() {

		/**
		 * Create tokens table if not exist
		 *
		 * @since 0.93
		 *
		 * @return void
		 */
		( new Tokens_Table )->create_table();

		/**
		 * Create logs table if not exist
		 *
		 * @since 0.94
		 *
		 * @return void
		 */
		( new Logs_DB )->create_table();

		/**
		 * Add the secret key if not exist
		 *
		 * @since 0.93
		 */
		$key = get_option( 'login_me_now_secret_key' );
		if ( ! $key ) {
			$key = Helper::generate_key();
			update_option( 'login_me_now_secret_key', $key );
		}

		/**
		 * Add the algorithm if not exist
		 *
		 * @since 0.93
		 */
		$algo = get_option( 'login_me_now_algorithm' );
		if ( ! $algo ) {
			$algo = 'HS256';
			update_option( 'login_me_now_algorithm', $algo );
		}
	}
}

new After_Activation();