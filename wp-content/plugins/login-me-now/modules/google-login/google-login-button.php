<?php
/**
 * @author  HeyMehedi
 * @since   0.98
 * @version 0.98
 */

namespace Login_Me_Now;

class Google_Login_Button {

	public function __construct() {
		add_action( 'init', array( $this, 'shortcodes' ) );
		
		$native_login = lmn_get_option( 'google_native_login' );
		if ( $native_login ) {
			add_action( 'login_form', array( $this, 'wp_login_form' ) );
			add_action( 'woocommerce_login_form_start', array( $this, 'wp_login_form' ) );
		}
	}

	public function wp_login_form() {?>
		<div id="wp-login-google-login-button">
			<div class="g_id_signin"
				data-type="standard"
				data-shape="rectangular"
				data-theme="outline"
				data-text="continue_with"
				data-size="large"
				data-logo_alignment="center"
				data-width="270">
			</div>
			<div style="text-align: center; margin: 10px 0;"><?php esc_html_e( 'Or', 'login-me-now' );?></div>
		</div>
	<?php }

	public function shortcodes() {
		add_shortcode( 'login_me_now_google_button', array( $this, 'login_btn' ) );
	}

	public function login_btn() {
		if ( ! is_user_logged_in() ) {
			$html = '<div class="g_id_signin"
			data-type="standard"
			data-shape="rectangular"
			data-theme="outline"
			data-text="continue_with"
			data-size="large"
			data-logo_alignment="center">
			</div>';

			return $html;
		}

		return '';
	}
}

new Google_Login_Button;