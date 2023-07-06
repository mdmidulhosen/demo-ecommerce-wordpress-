<?php
/**
 * @author  HeyMehedi
 * @since   0.94
 * @version 0.96
 */

/**
 * Return options.
 *
 * @param  string $option       Option key.
 * @param  mixed  $default      Option default value.
 * @return mixed                Return option value.
 */
function lmn_get_option( $option, $default = '' ) {

	$options = get_option( 'login_me_now_admin_settings', array() );
	$value   = ( isset( $options[$option] ) && '' !== $options[$option] ) ? $options[$option] : array();

	if ( array() === $value ) {
		$value = $default;
	}

	/**
	 * Dynamic filter lmn_get_option_$option.
	 * $option is the name of the Setting.
	 *
	 * @since  0.94
	 * @var Mixed.
	 */

	return apply_filters( "lmn_get_option_{$option}", $value, $option, $options );
}

/**
 * Get current user IP Address.
 *
 * @return string
 */
function get_ip_address() {
	if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) {
		return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ) );
	} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		// Proxy servers can send through this header like this: X-Forwarded-For: client1, proxy1, proxy2
		// Make sure we always only send through the first IP in the list which should always be the client IP.
		return (string) rest_is_ip_address( trim( current( preg_split( '/,/', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) ) ) );
	} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
		return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
	}

	return '0.0.0.0';
}

function lmn_get_pro_url() {
	return '#';
}
