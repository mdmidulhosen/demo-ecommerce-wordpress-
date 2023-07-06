<?php
/**
 * @author  HeyMehedi
 * @since   0.98
 * @version 0.98
 */

namespace Login_Me_Now;

class Google_Assets {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 50 );
		add_action( 'login_enqueue_scripts', array( $this, 'enqueue_scripts' ), 1 );
		add_action( 'login_footer', array( $this, 'wp_login_script' ), 50 );
	}

	public function wp_login_script() {?>
		<script type="text/javascript">
			jQuery("#wp-login-google-login-button").prependTo("#loginform");
		</script>
	<?php }

	public function enqueue_scripts() {
		wp_enqueue_script( 'login-me-now-google-client-js', 'https://accounts.google.com/gsi/client' );
	}
}

new Google_Assets;