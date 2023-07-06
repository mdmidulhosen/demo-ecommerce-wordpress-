<?php
/**
 * @author  HeyMehedi
 * @since   0.98
 * @version 0.98
 */

$google_login = lmn_get_option( 'google_login', false );
if ( ! $google_login ) {
	return;
}

require_once LOGIN_ME_NOW_MODULES . 'google-login/google-assets.php';
require_once LOGIN_ME_NOW_MODULES . 'google-login/google-authenticate.php';
require_once LOGIN_ME_NOW_MODULES . 'google-login/google-one-tap.php';
require_once LOGIN_ME_NOW_MODULES . 'google-login/google-login-button.php';