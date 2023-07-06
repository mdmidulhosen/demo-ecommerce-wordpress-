<?php
/**
 * @author  HeyMehedi
 * @since   2.0.0
 * @version 2.0.0
 */

/**
 * Return options.
 *
 * @param  string $option       Option key.
 * @param  mixed  $default      Option default value.
 * @return mixed                Return option value.
 */
function lmn_pro_get_option( $option, $default = '' ) {

	$options = get_option( 'login_me_now_admin_settings', array() );
	$value   = ( isset( $options[$option] ) && '' !== $options[$option] ) ? $options[$option] : array();

	if ( array() === $value ) {
		$value = $default;
	}

	/**
	 * Dynamic filter lmn_get_option_$option.
	 * $option is the name of the Setting.
	 *
	 * @since  2.0.0
	 * @var Mixed.
	 */

	return apply_filters( "lmn_get_option_{$option}", $value, $option, $options );
}
