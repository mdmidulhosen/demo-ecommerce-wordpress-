<?php
/**
 * @author  HeyMehedi
 * @since   0.98
 * @version 0.99
 */

namespace Login_Me_Now;

class Google_One_Tap {

	public function __construct() {
		add_action( 'wp_footer', array( $this, 'google_one_tap' ), 50 );
		add_action( 'login_footer', array( $this, 'google_one_tap' ), 50 );
	}

	public function google_one_tap() {
		$nonce   = wp_create_nonce( 'lmn-google-nonce' );
		$exclude = apply_filters( 'login_me_now_google_exclude_pages', false );

		if ( ! is_user_logged_in() && ! $exclude ) {
			global $wp;
			$client_id      = lmn_get_option( 'google_client_id' );
			$auto_signin    = lmn_get_option( 'google_auto_sign_in' );
			$cancel_outside = lmn_get_option( 'google_cancel_on_tap_outside', true );
			$current_url    = home_url( add_query_arg( array(), $wp->request ) );
			$login_uri      = home_url() . '/?lmn-google';
			?>
			<div id="g_id_onload"
				data-client_id="<?php echo esc_attr( $client_id ); ?>"
				data-context=""
				data-itp_support="true"
				data-wpnonce="<?php echo esc_attr( $nonce ); ?>"
				data-auto_select="<?php echo esc_attr( $auto_signin ? 'true' : 'false' ); ?>"
				data-redirect_uri="<?php echo esc_attr( $current_url ); ?>"
				data-cancel_on_tap_outside="<?php echo esc_attr( $cancel_outside ? 'true' : 'false' ); ?>"
				data-login_uri="<?php echo esc_attr( $login_uri ); ?>"
			</div>
		<?php }
	}
}

new Google_One_Tap;