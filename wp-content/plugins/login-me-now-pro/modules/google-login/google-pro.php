<?php
/**
 * @author  HeyMehedi
 * @since   2.0.0
 * @version 2.0.0
 */

namespace Login_Me_Now_Pro;

use WP_User;

class Google_Pro {

	public function __construct() {
		add_filter( 'login_me_now_google_login_redirect_url', array( $this, 'login_me_now_google_login_redirect_url' ) );
		add_filter( 'login_me_now_default_user_role', array( $this, 'login_me_now_default_user_role' ) );
		add_filter( 'login_me_now_google_exclude_pages', array( $this, 'exclude_pages' ) );

		add_action( 'wp_footer', array( $this, 'google_credential' ), 50 );
		add_action( 'login_footer', array( $this, 'google_credential' ), 50 );
	}

	public function login_me_now_google_login_redirect_url( $rd ) {
		$redirect_url = lmn_pro_get_option( 'google_pro_redirect_url' );

		if ( ! $redirect_url ) {
			return $rd;
		}

		return $redirect_url;
	}

	public function login_me_now_default_user_role( $user_id ) {
		$default_role = lmn_pro_get_option( 'google_pro_default_user_role' );

		if ( ! is_wp_error( $user_id ) && $default_role ) {
			$user = new WP_User( $user_id );
			$user->remove_role( 'subscriber' );
			$user->add_role( $default_role );
		}
	}

	/**
	 * If exclude pages not load the google credential
	 * then add this
	 */
	public function google_credential() {
		$exclude_pages = lmn_pro_get_option( 'google_pro_exclude_pages' );
		$current_id    = get_the_ID();
		$array         = explode( ',', $exclude_pages );

		if ( in_array( $current_id, $array ) ) {
			$nonce = wp_create_nonce( 'lmn-google-nonce' );
			if ( ! is_user_logged_in() ) {
				global $wp;
				$client_id   = lmn_pro_get_option( 'google_client_id' );
				$current_url = home_url( add_query_arg( array(), $wp->request ) );
				$login_uri   = home_url() . '/?lmn-google';
				?>

			<div id="g_id_onload"
				data-client_id="<?php echo esc_attr( $client_id ); ?>"
				data-wpnonce="<?php echo esc_attr( $nonce ); ?>"
				data-redirect_uri="<?php echo esc_attr( $current_url ); ?>"
				data-login_uri="<?php echo esc_attr( $login_uri ); ?>"
				data-context="signin"
				data-ux_mode="popup"
				data-auto_prompt="false">
			</div>
		<?php }
		}
	}

	public function exclude_pages() {
		$exclude_pages = lmn_pro_get_option( 'google_pro_exclude_pages' );
		$current_id    = get_the_ID();
		$array         = explode( ',', $exclude_pages );

		if ( in_array( $current_id, $array ) ) {
			return true;
		}

		return false;
	}
}

new Google_Pro;