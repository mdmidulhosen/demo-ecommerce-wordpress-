<?php
/**
 * @author  HeyMehedi
 * @since   0.98
 * @version 0.99
 */

namespace Login_Me_Now;

use Google_Client;
use WP_Error;

class Google_Authenticate {

	public function __construct() {
		add_action( 'init', array( $this, 'endpoint' ) );
	}

	public function endpoint() {

		if ( array_key_exists( 'lmn-google', $_GET ) ) {

			$nonce = ! empty( $_POST['wpnonce'] )
				? sanitize_text_field( wp_unslash( $_POST['wpnonce'] ) )
				: '';

			if ( ! wp_verify_nonce( $nonce, 'lmn-google-nonce' ) ) {
				error_log( 'Login Me Now - WP Nonce Verify Failed' );

				return;
			}

			if ( ! isset( $_POST['g_csrf_token'] ) && ! empty( $_POST['g_csrf_token'] ) ) {
				error_log( 'Login Me Now - Post g_csrf_token not available' );

				return;
			}

			if ( ! isset( $_COOKIE['g_csrf_token'] ) && ! empty( $_COOKIE['g_csrf_token'] ) ) {
				error_log( 'Login Me Now - Cookie g_csrf_token not available' );

				return;
			}

			if ( $_POST['g_csrf_token'] != $_COOKIE['g_csrf_token'] ) {
				error_log( 'Login Me Now - g_csrf_token is not same in post and cookie' );

				return;
			}

			if ( ! isset( $_POST['credential'] ) && ! empty( $_POST['credential'] ) ) {
				error_log( 'Login Me Now - Credential is not available' );

				return;
			}

			$id_token  = sanitize_text_field( wp_unslash( $_POST['credential'] ) );
			$client_id = lmn_get_option( 'google_client_id' );
			$client    = new Google_Client( array( 'client_id' => esc_html( $client_id ) ) );
			$payload   = $client->verifyIdToken( $id_token );

			if ( $payload ) {
				$wp_user      = get_user_by( 'email', sanitize_email( $payload['email'] ) );
				$redirect_uri = ! empty( $_POST['redirect_uri'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_uri'] ) ) : '';
				$redirect_uri = apply_filters( 'login_me_now_google_login_redirect_url', $redirect_uri );

				if ( $wp_user ) {
					$action = $this->login_user( $wp_user->ID, $payload, $redirect_uri );
				} else {
					$action = $this->register_user( $payload, $redirect_uri );
				}

				if ( is_wp_error( $action ) ) {
					error_log( 'Login Me Now - ' . print_r( $action ) );

					return;
				}

			} else {
				error_log( 'Login Me Now - invalid id' );

				return;
			}

		}
	}

	public function register_user( $payload, $redirect_uri ) {
		$errors = new WP_Error();

		$username_parts = array();
		if ( isset( $payload['given_name'] ) ) {
			$username_parts[] = sanitize_user( $payload['given_name'], true );
		}

		if ( isset( $payload['family_name'] ) ) {
			$username_parts[] = sanitize_user( $payload['family_name'], true );
		}

		if ( empty( $username_parts ) ) {
			$email_parts      = explode( '@', $payload['email'] );
			$email_username   = $email_parts[0];
			$username_parts[] = sanitize_user( $email_username, true );
		}

		$username = strtolower( implode( '.', $username_parts ) );

		$default_user_name = $username;
		$suffix            = 1;
		while ( username_exists( $username ) ) {
			$username = $default_user_name . $suffix;
			$suffix++;
		}
		$new_user_id = register_new_user( sanitize_user( $username ), $payload['email'] );

		if ( is_wp_error( $new_user_id ) ) {
			$errors->add( 'registration_failed', __( '<strong>Error</strong>: Registration Failed', 'login-me-now' ) );
		}

		$user_data                 = array();
		$user_data['ID']           = $new_user_id;
		$user_data['first_name']   = $payload['given_name'];
		$user_data['last_name']    = $payload['family_name'];
		$user_data['display_name'] = $payload['name'];
		$user_id                   = wp_update_user( $user_data );

		do_action( 'login_me_now_default_user_role', $user_id );

		update_user_meta( $new_user_id, 'login_me_now_profile_picture_url', esc_url_raw( $payload['picture'] ) );
		update_user_meta( $new_user_id, 'nickname', $payload['given_name'] );

		wp_set_current_user( $new_user_id );
		wp_set_auth_cookie( $new_user_id, true );

		if ( $errors->has_errors() ) {
			return $errors;
		}

		if ( wp_safe_redirect( $redirect_uri ) ) {
			exit;
		}
	}

	public function login_user( $id, $payload, $redirect_uri ) {
		$update_existing_data = lmn_get_option( 'google_update_existing_user_data', false );

		if ( $update_existing_data ) {
			$user_data                 = array();
			$user_data['ID']           = $id;
			$user_data['first_name']   = $payload['given_name'];
			$user_data['last_name']    = $payload['family_name'];
			$user_data['display_name'] = $payload['name'];

			wp_update_user( $user_data );
			update_user_meta( $id, 'nickname', $payload['given_name'] );
		}

		update_user_meta( $id, 'login_me_now_profile_picture_url', esc_url_raw( $payload['picture'] ) );
		wp_clear_auth_cookie();
		wp_set_current_user( $id );
		wp_set_auth_cookie( $id, true );

		if ( wp_safe_redirect( $redirect_uri ) ) {
			exit;
		}
	}
}

new Google_Authenticate;