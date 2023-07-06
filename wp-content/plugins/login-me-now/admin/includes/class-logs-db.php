<?php
/**
 * @author  HeyMehedi
 * @since   0.94
 * @version 0.96
 */

namespace Login_Me_Now;

/**
 * Logs related methods and actions
 *
 * @since 0.94
 * @version 0.96
 */
class Logs_DB {

	/**
	 * Create logs table if not exist
	 *
	 * @since 0.94
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
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}login_me_now_logs (
			`id` bigint(20) NOT NULL AUTO_INCREMENT,
			`user_id` bigint(20) NOT NULL,
			`ip` varchar(260) DEFAULT NULL,
			`message` varchar(260) DEFAULT NULL,
			`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`),
			KEY `user_id` (`user_id`)
			) ENGINE=InnoDB $collate;",
		);

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		foreach ( $table_schema as $table ) {
			dbDelta( $table );
		}
	}

	/**
	 * Insert log data
	 *
	 * @since 0.94
	 *
	 * @return void
	 */
	public static function insert( Int $user_id, String $message ) {
		$save_logs = lmn_get_option( 'logs', true );
		if ( ! $save_logs ) {
			return;
		}

		global $wpdb;

		$ip = get_ip_address();

		$checkin_sql = sprintf( "INSERT INTO {$wpdb->prefix}login_me_now_logs
		(user_id, ip, message)
		VALUES
		('%s', '%s', '%s')",
			intval( $user_id ), $ip, $message );

		$wpdb->query( $checkin_sql );
	}

	/**
	 * Delete logs
	 *
	 * @since 0.94
	 *
	 * @return void
	 */
	public static function delete_logs( $days_old ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'login_me_now_logs';

		$seven_days_ago = date( 'Y-m-d H:i:s', strtotime( "-$days_old days" ) );

		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $table_name WHERE created_at < %s",
				$seven_days_ago
			)
		);

	}
}