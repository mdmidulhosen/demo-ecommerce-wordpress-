<?php
/**
 * @author  HeyMehedi
 * @since   0.96
 * @version 0.96
 */

namespace Login_Me_Now;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin_Notice {

	protected static $instance = null;

	private function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'admin_notices', array( $this, 'user_switching' ) );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function user_switching() {
		?>
		<div class="error">
			<p>
			<?php _e( 'Since you are using Login Me Now, you can deactivate the <strong>User Switching</strong> plugin, If you are not interested to deactivate the User Switching plugin, simply turn off the `User Switching` from <strong>Dashboard -> Settings -> General -> User Switching</strong>..',
			'login-me-now' );?>
			</p>
		</div>
	<?php }
}

Admin_Notice::instance();