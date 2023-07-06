<?php
/**
 * @author  HeyMehedi
 * @since   0.98
 * @version 0.98
 */

$user_switching = lmn_get_option( 'user_switching', true );
if ( ! $user_switching ) {
	return;
}

require_once LOGIN_ME_NOW_MODULES . 'user-switching/user-switching.php';