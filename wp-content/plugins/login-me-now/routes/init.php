<?php
/**
 * @author  HeyMehedi
 * @since   0.90
 * @version 0.90
 */

namespace Login_Me_Now\Routes;

class API_Init {
	public function __construct() {
		require_once LOGIN_ME_NOW_BASE_DIR . 'routes/base.php';
		require_once LOGIN_ME_NOW_BASE_DIR . 'routes/generate.php';
		require_once LOGIN_ME_NOW_BASE_DIR . 'routes/validate.php';
		require_once LOGIN_ME_NOW_BASE_DIR . 'routes/autologin.php';
	}
}

new API_Init;