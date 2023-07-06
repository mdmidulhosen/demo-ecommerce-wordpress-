<?php
/*
Plugin Name: Login Me Now
Plugin URI: https://wordpress.org/plugins/login-me-now/
Description: Simple and Timer Saver One Click Login WordPress Plugin for Chrome Extension
Author: Login Me Now
Author URI: https://loginmenow.com/
Text Domain: login-me-now
Requires PHP: 7.4
Domain Path: /languages
Version: 0.99
 */

defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( defined( 'LOGIN_ME_NOW_VERSION' ) ) {
	return;
}


define( 'LOGIN_ME_NOW_VERSION', '0.99' );
define( 'LOGIN_ME_NOW_PRO_UPGRADE_URL', 'https://halalbrains.com/login-me-now/' );

define( 'LOGIN_ME_NOW_BASE_DIR', plugin_dir_path( __FILE__ ) );
define( 'LOGIN_ME_NOW_BASE_URL', plugin_dir_url( __FILE__ ) );
define( 'LOGIN_ME_NOW_MODULES', plugin_dir_path( __FILE__ ) . 'modules/' );

/**
 * Load dependencies managed by composer
 */
require_once LOGIN_ME_NOW_BASE_DIR . 'vendor/autoload.php';

/**
 * Load necessary classes
 */
require_once LOGIN_ME_NOW_BASE_DIR . 'includes/init.php';
require_once LOGIN_ME_NOW_BASE_DIR . 'modules/init.php';
require_once LOGIN_ME_NOW_BASE_DIR . 'admin/class-login-me-now-admin-loader.php';
require_once LOGIN_ME_NOW_BASE_DIR . 'routes/init.php';