<?php
/**
 * @author  HeyMehedi
 * @since   0.90
 * @version 0.97
 */

namespace Login_Me_Now;

class Helper {

	public static function get_file_uri( $path ) {
		$file = LOGIN_ME_NOW_BASE_URL . $path;

		return $file;
	}

	public static function get_file_dir() {
		$file = LOGIN_ME_NOW_BASE_DIR;

		return $file;
	}

	public static function get_template_part( $template, $args = array() ) {

		if ( is_array( $args ) ) {
			extract( $args );
		}

		$template = '/templates/' . $template . '.php';

		$file = self::get_file_dir() . $template;

		require $file;
	}

	/**
	 * Randomly Generate a Key
	 *
	 * @return string
	 **/
	public static function generate_key() {
		$characters = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTWYXZ";
		$key        = '';
		for ( $i = 0; $i < 40; $i++ ) {
			$key .= $characters[rand( 0, 40 )];
		}

		return $key;
	}

	public static function get_users_tokens() {
		global $wpdb;
		$tokens = $wpdb->get_results( "SELECT * FROM $wpdb->usermeta WHERE meta_key='login_me_now_track_token'", ARRAY_A );

		return $tokens;
	}

	public static function generate_status_options( $active, $id ) {
		$status = array(
			'active'  => __( 'Active', 'login-me-now' ),
			'blocked' => __( 'Block', 'login-me-now' ),
			'pause'   => __( 'Pause', 'login-me-now' ),
		);

		if ( 'expired' === $active ) {
			return __( '<span style="color:red;">Expired</span>', 'login-me-now' );
		}

		$html = '<select onchange="updateStatus(event)" data-id="' . $id . '">';
		foreach ( $status as $key => $value ) {
			$html .= sprintf( '<option %s value="%s">%s</option>', ( $key === $active ? 'selected' : '' ), $key, $value );
		}
		$html .= "</select>";

		return $html;
	}
}