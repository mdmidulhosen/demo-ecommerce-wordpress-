<?php
/**
 * @author  Login Me Now
 * @since   0.97
 * @version 0.97
 */

namespace Login_Me_Now;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Tokens related methods and actions
 *
 * @since 0.97
 */
class Tokens_List_Table extends \WP_List_Table {

	public $current_time;

	public function __construct() {
		$this->current_time = time();
		parent::__construct( array(
			'singular' => 'lmntoken',
			'plural'   => 'lmntokens',
			'ajax'     => false,
		) );
	}

	/**
	 * Get all tokens
	 *
	 * @since 0.97
	 *
	 * @return Array|Object|NULL
	 */
	public static function get_tokens( $per_page, $current_page ) {
		global $wpdb;

		// Calculate the offset based on the current page and number of results per page
		$offset = ( $current_page - 1 ) * $per_page;

		$sql = "SELECT * FROM {$wpdb->prefix}login_me_now_tokens
			ORDER BY id DESC
			LIMIT %d, %d";

		$count        = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}login_me_now_tokens" );
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
			case 'token_id':
				return $item->token_id;
			case 'user_login':
				$user_info = get_userdata( $item->user_id );
				$email     = ! empty( $user_info->user_email ) ? $user_info->user_email : '-';

				return $email;
			case 'issued_at':
				return ! empty( $item->created_at ) ? ( date( 'M d, Y, h:i A', $item->created_at ) ) : __( 'Not set', 'login-me-now' );
			case 'expire':
				return date( 'M d, Y, h:i A', $item->expire );
			case 'status':
				if ( $item->expire < $this->current_time ) {
					$item->status = 'expired';
				}

				Helper::generate_status_options( $item->status, $item->id );

				return Helper::generate_status_options( $item->status, $item->id );

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
			'token_id'   => __( 'Token ID', 'login-me-now' ),
			'user_login' => __( 'User Email', 'login-me-now' ),
			'issued_at'  => __( 'Issued At', 'login-me-now' ),
			'expire'     => __( 'Expire', 'login-me-now' ),
			'status'     => __( 'Status', 'login-me-now' ),
		);

		return $columns;
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

		$tokens      = $this->get_tokens( $per_page, $current_page );
		$this->items = $tokens['result'];
		$count       = $tokens['count'];

		$this->set_pagination_args( array(
			'total_items' => $count,
			'per_page'    => $per_page,
		) );
	}
}