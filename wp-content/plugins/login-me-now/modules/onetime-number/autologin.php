<?php
/**
 * @author  HeyMehedi
 * @since   0.90
 * @version 0.99
 */

namespace Login_Me_Now;

use Login_Me_Now\Helper;
use Login_Me_Now\Logs_DB;

class AutoLogin {

	public function __construct() {
		add_action( 'init', array( $this, 'using_onetime_number' ) );
	}

	public function using_onetime_number() {
		if ( ! isset( $_GET['lmn'] ) ) {
			return;
		}

		if ( empty( $_GET['lmn'] ) ) {
			$title   = __( 'Number Not Provided', 'login-me-now' );
			$message = __( 'Request a new access link in order to obtain dashboard access', 'login-me-now' );
			Helper::get_template_part( 'messages/error', array( 'title' => $title, 'message' => $message ) );
			exit();
		}

		/** First thing, check the secret number if not exist return an error*/
		$number  = sanitize_text_field( $_GET['lmn'] );
		$t_value = get_transient( $number );
		if ( ! $t_value ) {
			$title   = __( 'Invalid number', 'login-me-now' );
			$message = __( 'Request a new access link in order to obtain dashboard access', 'login-me-now' );
			Helper::get_template_part( 'messages/error', array( 'title' => $title, 'message' => $message ) );
			exit();
		}

		$user_id = ! empty( $t_value ) ? $t_value : false;
		if ( ! $user_id ) {
			$title   = __( 'User not found', 'login-me-now' );
			$message = __( 'Request a new access link in order to obtain dashboard access', 'login-me-now' );
			Helper::get_template_part( 'messages/error', array( 'title' => $title, 'message' => $message ) );
			exit();
		}

		delete_transient( $number );

		$message = __( "Logged in using onetime link", 'login-me-now' );
		if ( ! empty( $_GET['extension'] ) ) {
			$message = __( "Logged in using Chrome Extension", 'login-me-now' );
		}
		( new Logs_DB )->insert( $user_id, $message );

		$this->now( $user_id );
	}

	public function now( $user_id ) {
		include ABSPATH . "wp-includes/pluggable.php";
		wp_clear_auth_cookie();
		wp_set_auth_cookie( $user_id, true );

		$title   = __( 'Authentication Success 🎉', 'login-me-now' );
		$message = __( 'You are being redirected to the dashboard', 'login-me-now' );
		Helper::get_template_part( 'messages/success', array( 'title' => $title, 'message' => $message ) );
	}
}

new AutoLogin;