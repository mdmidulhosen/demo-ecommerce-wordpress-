<?php
/**
 * @author  Login Me Now
 * @since   0.96
 * @version 0.97
 */

namespace Login_Me_Now;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Logs related methods and actions
 *
 * @since 0.96
 */
class Logs_List_Table extends \WP_List_Table {

	public function __construct() {
		parent::__construct( array(
			'singular' => 'lmnlog',
			'plural'   => 'lmnlogs',
			'ajax'     => false,
		) );
	}

	/**
	 * Get all logs
	 *
	 * @since 0.96
	 *
	 * @return Array|Object|NULL
	 */
	public static function get_logs( $per_page, $current_page ) {
		global $wpdb;

		// Calculate the offset based on the current page and number of results per page
		$offset = ( $current_page - 1 ) * $per_page;

		$sql = "SELECT * FROM {$wpdb->prefix}login_me_now_logs
			ORDER BY id DESC
			LIMIT %d, %d";

		$count        = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}login_me_now_logs" );
		$prepared_sql = $wpdb->prepare( $sql, $offset, $per_page );
		$result       = $wpdb->get_results( $prepared_sql );

		return array(
			'result' => $result,
			'count'  => $count,
		);
	}

	public function get_table_classes() {
		return array( 'widefat', 'fixed', 'striped', $this->_args['plural'] );
	}

	/**
	 * Message to show if no designation found
	 *
	 * @return void
	 */
	public function no_items() {
		_e( 'No items found', 'login-me-now' );
	}

	/**
	 * Default column values if no callback found
	 *
	 * @param  object  $item
	 * @param  string  $column_name
	 *
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'user_login':
				$user_id   = isset( $item->user_id ) ? $item->user_id : 0;
				$user_info = get_userdata( $user_id );
				$email     = ! empty( $user_info->user_email ) ? $user_info->user_email : '-';

				return $email;
			case 'message':
				return $item->message;
			case 'created_at':
				return ! empty( $item->created_at ) ? ( date( 'M d, Y, h:i A', strtotime( $item->created_at ) ) ) : __( 'Not set', 'login-me-now' );
			case 'ip_address':
				return $item->ip;
			default:
				return isset( $item->$column_name ) ? $item->$column_name : '';
		}
	}

	/**
	 * Get the column names
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'user_login' => __( 'User Email', 'login-me-now' ),
			'message'    => __( 'Message', 'login-me-now' ),
			'created_at' => __( 'Time', 'login-me-now' ),
			'ip_address' => __( 'IP Address', 'login-me-now' ),
		);

		return $columns;
	}

	/**
	 * Render the title column
	 *
	 * @param  object  $item
	 *
	 * @return string
	 */
	public function column_title( $item ) {
		$user_id    = isset( $item->user_id ) ? $item->user_id : 0;
		$user_info  = get_userdata( $user_id );
		$ip_address = isset( $item->ip ) ? $item->ip : 0;
		$time       = isset( $item->created_at ) ? $item->created_at : 0;
		$message    = isset( $item->message ) ? $item->message : 0;

		return sprintf( "<a href='#'>%s</a> %s <i>at</i> <b>%s</b> <i>from</i> %s", esc_html( $user_info->display_name ), esc_html( strtolower( $message ) ), date( 'M d, Y, h:i A', strtotime( $time ) ), esc_html( $ip_address ) );
	}

	/**
	 * Prepare the items
	 *
	 * @return void
	 */
	public function prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$per_page     = 50;
		$current_page = $this->get_pagenum();

		$logs        = $this->get_logs( $per_page, $current_page );
		$this->items = $logs['result'];
		$count       = $logs['count'];

		$this->set_pagination_args( array(
			'total_items' => $count,
			'per_page'    => $per_page,
		) );
	}
}