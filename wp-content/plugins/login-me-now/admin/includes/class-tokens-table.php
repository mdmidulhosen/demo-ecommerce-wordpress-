<?php
/**
 * @author  HeyMehedi
 * @since   0.93
 * @version 0.94
 */

namespace Login_Me_Now;

/**
 * Tokens related methods and actions
 *
 * @since 0.93
 */
class Tokens_Table {
	/**
	 * Create tokens table if not exist
	 *
	 * @since 0.93
	 *
	 * @return void
	 */
	public function create_table() {
		global $wpdb;

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( ! empty( $wpdb->charset ) ) {
				$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			}

			if ( ! empty( $wpdb->collate ) ) {
				$collate .= " COLLATE $wpdb->collate";
			}
		}

		$table_schema = array(
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}login_me_now_tokens (
			`id` bigint(20) NOT NULL AUTO_INCREMENT,
			`user_id` bigint(20) NOT NULL,
			`token_id` bigint(20) NOT NULL,
			`count`  bigint(20) NOT NULL,
			`expire`  bigint(20) NOT NULL,
			`status` varchar(260) DEFAULT NULL,
			`created_at` varchar(260) DEFAULT NULL,
			PRIMARY KEY (`id`),
			KEY `user_id` (`user_id`),
			KEY `token_id` (`token_id`)
			) ENGINE=InnoDB $collate;",
		);

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		foreach ( $table_schema as $table ) {
			dbDelta( $table );
		}
	}

	public function alter_table() {
		global $wpdb;

		$table_schema = array(
			"ALTER TABLE {$wpdb->prefix}login_me_now_tokens ADD `count` bigint(20) NOT NULL AFTER `token_id`,
			ADD `created_at` varchar(260) DEFAULT NULL AFTER `status`;",
		);

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		foreach ( $table_schema as $table ) {
			$wpdb->query( $table );
		}
	}

	/**
	 * Insert token data
	 *
	 * @since 0.93
	 *
	 * @return void
	 */
	public static function insert( Int $user_id, Int $token_id, Int $expire, String $status ) {
		global $wpdb;

		$checkin_sql = sprintf( "INSERT INTO {$wpdb->prefix}login_me_now_tokens
		(user_id, token_id, expire, status, created_at)
		VALUES
		('%s', '%s', '%s', '%s', '%s')",
			intval( $user_id ), $token_id, $expire, $status, time() );

		$wpdb->query( $checkin_sql );
	}

	/**
	 * Update token status
	 *
	 * @since 0.93
	 *
	 * @return void
	 */
	public static function update( Int $id, String $status ) {
		global $wpdb;

		$updated = $wpdb->query( sprintf( "UPDATE {$wpdb->prefix}login_me_now_tokens
			SET status ='%s' WHERE id='%d'",
			sanitize_text_field( $status ), sanitize_text_field( intval( $id ) ) ) );

		if ( ! $updated ) {
			wp_send_json_error( __( "Something wen't wrong", 'login-me-now' ) );
		}

		wp_send_json_success( __( "Status updated", 'login-me-now' ) );
	}

	/**
	 * Get all tokens
	 *
	 * @since 0.93
	 *
	 * @return Array|Object|NULL
	 */
	public static function get_tokens() {
		global $wpdb;

		$sql = "SELECT * FROM {$wpdb->prefix}login_me_now_tokens
			ORDER BY id
			DESC";

		$result = $wpdb->get_results( $sql );

		return $result;
	}

	/**
	 * Get token status
	 *
	 * @since 0.93
	 *
	 * @return String
	 */
	public static function get_token_status( Int $token_id ) {
		global $wpdb;

		$record = $wpdb->get_row(
			sprintf( "SELECT * FROM {$wpdb->prefix}login_me_now_tokens
			WHERE token_id='%s'",
				$token_id )
		);

		$status = ! empty( $record->status ) ? $record->status : 'invalid';

		return $status;
	}
}

new Tokens_Table();