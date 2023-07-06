<?php
/**
 * @author  HeyMehedi
 * @since   0.94
 * @version 0.99
 */

namespace Login_Me_Now;

use WP_Error;
use WP_User;

/**
 * The Onetime Number Handling Class
 */
class Onetime_Number {

	
	/**
	 * Get the shareable onetime link
	 *
	 * @param Integer $user_id
	 *
	 * @return string|WP_Error|null
	 */
	public function get_shareable_link( Int $user_id, Int $expiration ) {
		$user = get_userdata( $user_id );
		if ( ! $user ) {
			return;
		}

		$number = $this->get_new( $user, $expiration );
		if ( ! $number ) {
			return;
		}

		$link = sprintf( '%s%s', admin_url( '/?lmn=' ), $number );

		return array( 'link' => $link, 'number' => $number );
	}

	/**
	 * Generate a Onetime Number
	 *
	 * @param WP_User $user
	 * @param Integer $hour for expiration
	 *
	 * @return mixed|WP_Error|null
	 */
	private function get_new( WP_User $user, Int $hours ) {

		/** Valid credentials, the user exists create the according Token */
		$issuedAt = time();
		$expire   = apply_filters( 'login_me_now_onetime_number_expire', $issuedAt + ( HOUR_IN_SECONDS * $hours ), $issuedAt );

		$number = $this->rand_number();

		/** Store the generated token in transient*/
		$saved = set_transient( $number, $user->data->ID, $expire );
		if ( ! $saved ) {
			return new WP_Error(
				__( "Something wen't wrong, please try again.", 'login-me-now' ),
			);
		}

		return $number;
	}

	/**
	 * Verify the number
	 *
	 * @param bool|string $number
	 *
	 * @return WP_Error | False | Integer
	 */
	public function verify( Int $number ) {
		$len = strlen( $number );

		/**
		 * if the number is not valid return an error.
		 */
		if ( ! $number || 16 !== $len ) {
			return new WP_Error(
				'Invalid Number'
			);
		}

		/** Get the user_id from transient */
		$user_id = (int) get_transient( $number );

		$user = get_userdata( $user_id );
		if ( ! $user ) {
			return false;
		}

		return $user->data->ID;
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
}