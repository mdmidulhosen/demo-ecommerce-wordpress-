<?php
/**
 * Login Me Now Pro Updater.
 *
 * @package Login Me Now
 * @since 2.0.0
 * @version 2.0.0
 */

namespace Login_Me_Now_Pro;

use HeyMehedi\EDD_SL_Plugin_Updater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

defined( 'ABSPATH' ) || exit;

/**
 * Class Updater
 *
 * @since 2.0.0
 */
class Updater {
	public $api_url;
	public $plugin_file;
	public $version;
	public $product_id;
	public $author;
	public $lic_key;

	public function __construct() {
		$this->api_url     = 'https://loginmenow.com';
		$this->plugin_file = LOGIN_ME_NOW_PRO_BASE_FILE;
		$this->version     = LOGIN_ME_NOW_PRO_VERSION;
		$this->product_id  = '241';
		$this->author      = 'Login Me Now';
		$this->lic_key     = (string) lmn_pro_get_option( 'lmn_pro_lic' );

		add_action( 'admin_init', array( $this, 'init_updater' ) );
	}

	public function init_updater() {
		$updater = new EDD_SL_Plugin_Updater(
			$this->api_url,
			$this->plugin_file,
			array(
				'version' => $this->version,
				'license' => $this->lic_key,
				'item_id' => $this->product_id,
				'author'  => $this->author,
				'beta'    => false,
			)
		);
	}
}

new Updater();