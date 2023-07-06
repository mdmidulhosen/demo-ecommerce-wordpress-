<?php
/*
Plugin Name: Login Me Now Pro
Plugin URI: https://wordpress.org/plugins/login-me-now/
Description: Simple and Timer Saver One Click Login WordPress Plugin for Chrome Extension
Author: Login Me Now
Author URI: https://loginmenow.com/
Text Domain: login-me-now-pro
Domain Path: /languages
Version: 2.0.0
 */

defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( defined( 'LOGIN_ME_NOW_PRO_VERSION' ) ) {
	return;
}

define( 'LOGIN_ME_NOW_PRO_VERSION', '2.0.0' );

define( 'LOGIN_ME_NOW_PRO_BASE_FILE', plugin_basename( __FILE__ ) );
define( 'LOGIN_ME_NOW_PRO_BASE_NAME', plugin_basename( __DIR__ ) );

define( 'LOGIN_ME_NOW_PRO_BASE_DIR', plugin_dir_path( __FILE__ ) );
define( 'LOGIN_ME_NOW_PRO_BASE_URL', plugin_dir_url( __FILE__ ) );
define( 'LOGIN_ME_NOW_PRO_MODULES', plugin_dir_path( __FILE__ ) . 'modules/' );

/**
 * Load dependencies managed by composer
 */
require_once LOGIN_ME_NOW_PRO_BASE_DIR . 'vendor/autoload.php';

/**
 * Load necessary classes
 */
require_once LOGIN_ME_NOW_PRO_BASE_DIR . 'admin/class-login-me-now-admin-loader.php';
require_once LOGIN_ME_NOW_PRO_BASE_DIR . 'modules/init.php';
