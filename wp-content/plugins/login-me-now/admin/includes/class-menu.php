<?php
/**
 * Class Menu.
 *
 * @package Login Me Now
 * @since 0.95
 * @version 0.98
 */

namespace Login_Me_Now;

use Login_Me_Now\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Menu {

	/**
	 * Instance
	 *
	 * @access private
	 * @var null $instance
	 * @since 0.94
	 */
	private static $instance;

	/**
	 * Initiator
	 *
	 * @since 0.94
	 * @return object initialized object of class.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			/** @psalm-suppress InvalidPropertyAssignmentValue */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
			self::$instance = new self();
			/** @psalm-suppress InvalidPropertyAssignmentValue */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		}

		return self::$instance;
	}

	/**
	 * Page title
	 *
	 * @since 0.94
	 * @var string $page_title
	 */
	public static $page_title = 'Login Me Now';

	/**
	 * Plugin slug
	 *
	 * @since 0.94
	 * @var string $plugin_slug
	 */
	public static $plugin_slug = 'login-me-now';

	/**
	 * Constructor
	 *
	 * @since 0.94
	 */
	public function __construct() {
		$this->initialize_hooks();
	}

	/**
	 * Init Hooks.
	 *
	 * @since 0.94
	 * @return void
	 */
	public function initialize_hooks() {

		self::$page_title  = apply_filters( 'login_me_now_page_title', __( 'Login Me Now', 'login-me-now' ) );
		self::$plugin_slug = self::$plugin_slug;

		add_action( 'admin_menu', array( $this, 'setup_menu' ) );
		add_action( 'admin_init', array( $this, 'settings_admin_scripts' ) );

		add_action( 'after_setup_theme', array( $this, 'init_admin_settings' ), 99 );
	}

	/**
	 * Admin settings init.
	 *
	 * @since 0.94
	 */
	public function init_admin_settings() {
		if ( ! is_customize_preview() ) {
			add_action( 'admin_head', array( $this, 'admin_submenu_css' ) );
		}
	}

	/**
	 * Add custom CSS for admin area sub menu icons.
	 *
	 * @since 0.94
	 */
	public function admin_submenu_css() {
		echo '<style class="astra-menu-appearance-style">
				#toplevel_page_' . esc_attr( self::$plugin_slug ) . ' .wp-menu-image.svg {
					background-size: 18px auto !important;
				}
			</style>';
	}

	/**
	 *  Initialize after Login Me Now gets loaded.
	 *
	 * @since 0.94
	 */
	public function settings_admin_scripts() {
		// Enqueue admin scripts.
		/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		if ( ! empty( $_GET['page'] ) && ( self::$plugin_slug === $_GET['page'] || false !== strpos( $_GET['page'], self::$plugin_slug . '_' ) ) ) { //phpcs:ignore
			/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
			add_action( 'admin_enqueue_scripts', array( $this, 'styles_scripts' ) );
			add_filter( 'admin_footer_text', array( $this, 'admin_footer_link' ), 99 );
		}
	}

	/**
	 * Add submenu to admin menu.
	 *
	 * @since 0.94
	 */
	public function setup_menu() {
		global $submenu;
		$capability = 'manage_options';

		if ( ! current_user_can( $capability ) ) {
			return;
		}

		$login_me_now_icon = apply_filters( 'menu_icon', 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyNS40LjEsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCA1MzMuNzYgNTMzLjc2IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1MzMuNzYgNTMzLjc2OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+DQo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPg0KCS5zdDB7ZmlsbDojRTRFNUUzO30NCgkuc3Qxe2ZpbGw6I0FCQkNERDt9DQo8L3N0eWxlPg0KPHBhdGggY2xhc3M9InN0MCIgZD0iTTIxLjMzLDI3Ljg0bDIxMC45LDQ4MC43OGMxLjMzLDQuMTUsNi44Nyw0LjkxLDkuMjYsMS4yN2w5MS4yMi0xNzUuNjljMC4zOS0wLjU5LDAuODktMS4wOSwxLjQ4LTEuNDgNCglsMTc1LjY5LTkxLjIyYzMuNjQtMi4zOSwyLjg4LTcuOTQtMS4yNy05LjI2TDI3Ljg0LDIxLjMzQzIzLjgzLDIwLjA1LDIwLjA1LDIzLjgzLDIxLjMzLDI3Ljg0eiIvPg0KPHBhdGggY2xhc3M9InN0MSIgZD0iTTUwOC42MiwyMzIuMjNMMjcuODQsMjEuMzNjLTEuNzctMC41Ny0zLjQzLTAuMDQtNC43LDAuOTZsMzA5LjMxLDMxMi40bDAuMjYtMC40OQ0KCWMwLjM5LTAuNTksMC44OS0xLjA5LDEuNDgtMS40OGwxNzUuNjktOTEuMjJDNTEzLjUzLDIzOS4xLDUxMi43NywyMzMuNTYsNTA4LjYyLDIzMi4yM3oiLz4NCjwvc3ZnPg0K' );
		$priority          = apply_filters( 'menu_priority', 59 );

		add_menu_page(
			self::$page_title,
			self::$page_title,
			$capability,
			self::$plugin_slug,
			array( $this, 'render_admin_dashboard' ),
			$login_me_now_icon,
			$priority
		);

		add_submenu_page(
			self::$plugin_slug,
			__( 'Settings', 'login-me-now' ),
			__( 'Settings', 'login-me-now' ),
			$capability,
			'login-me-now-settings',
			array( $this, 'tokens_callback' )
		);

		add_submenu_page(
			self::$plugin_slug,
			__( 'Tokens', 'login-me-now' ),
			__( 'Tokens', 'login-me-now' ),
			$capability,
			'login-me-now-tokens',
			array( $this, 'tokens_callback' )
		);

		add_submenu_page(
			self::$plugin_slug,
			__( 'Logs', 'login-me-now' ),
			__( 'Logs', 'login-me-now' ),
			$capability,
			'login-me-now-logs',
			array( $this, 'logs_callback' )
		);

		// Rewrite the menu item.
		$submenu[self::$plugin_slug][0][0] = __( 'Dashboard', 'login-me-now' );
		$submenu[self::$plugin_slug][1][2] = 'admin.php?page=login-me-now&path=settings';
	}

	/**
	 * Render Tokens
	 * @return void
	 */
	public function tokens_callback() {
		Helper::get_template_part( 'menu-page/token-status', new Tokens_List_Table );
	}

	/**
	 * Render Logs
	 * @return void
	 */
	public function logs_callback() {
		Helper::get_template_part( 'menu-page/all-logs', new Logs_List_Table );
	}

	/**
	 * Renders the admin settings.
	 *
	 * @since 0.94
	 * @return void
	 */
	public function render_admin_dashboard() {
		$page_action = '';

		if ( isset( $_GET['action'] ) ) { //phpcs:ignore
			/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$page_action = sanitize_text_field( wp_unslash( $_GET['action'] ) ); //phpcs:ignore
			/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$page_action = str_replace( '_', '-', $page_action );
		}

		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		include_once LOGIN_ME_NOW_ADMIN_DIR . 'views/admin-base.php';
		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
	}

	/**
	 * Enqueues the needed CSS/JS for the builder's admin settings page.
	 *
	 * @since 0.94
	 */
	public function styles_scripts() {

		if ( is_customize_preview() ) {
			return;
		}

		wp_enqueue_style( 'astra-admin-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500&display=swap', array(), LOGIN_ME_NOW_VERSION ); // Styles.

		wp_enqueue_style( 'wp-components' );

		/** @psalm-suppress UndefinedClass */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$show_self_branding = true;
		/** @psalm-suppress UndefinedClass */// phpcs:ignore Generic.Commenting.DocComment.MissingShort

		$localize = array(
			'current_user'           => ! empty( wp_get_current_user()->user_firstname ) ? ucfirst( wp_get_current_user()->user_firstname ) : ucfirst( wp_get_current_user()->display_name ),
			'admin_base_url'         => admin_url(),
			'plugin_dir'             => LOGIN_ME_NOW_BASE_URL,
			'plugin_ver'             => defined( 'LOGIN_ME_NOW_PRO_VERSION' ) ? LOGIN_ME_NOW_PRO_VERSION : '',
			'version'                => LOGIN_ME_NOW_VERSION,
			'pro_available'          => defined( 'LOGIN_ME_NOW_PRO_VERSION' ) ? true : false,
			'pro_installed_status'   => 'installed' === self::get_plugin_status( 'login-me-now-pro/login-me-now-pro.php' ) ? true : false,
			'product_name'           => __( 'Login Me Now', 'login-me-now' ),
			'plugin_name'            => __( 'Login Me Now PRO', 'login-me-now' ),
			'ajax_url'               => admin_url( 'admin-ajax.php' ),
			'show_self_branding'     => $show_self_branding,
			'admin_url'              => admin_url( 'admin.php' ),
			'home_slug'              => self::$plugin_slug,
			'upgrade_url'            => LOGIN_ME_NOW_PRO_UPGRADE_URL,
			'extension_url'          => 'https://chrome.google.com/webstore/detail/login-me-now/kkkofomlfhbepmpiplggmfpomdnkljoh/?source=wp-dashboard',
			'login_me_now_base_url'  => admin_url( 'admin.php?page=' . self::$plugin_slug ),
			'logo_url'               => apply_filters( 'login_me_now_admin_menu_icon', LOGIN_ME_NOW_BASE_URL . 'admin/assets/images/icon.png' ),
			'update_nonce'           => wp_create_nonce( 'login_me_now_update_admin_setting' ),
			'extensions'             => self::get_pro_extensions(),
			'plugin_manager_nonce'   => wp_create_nonce( 'login_me_now_plugin_manager_nonce' ),
			'generate_token_nonce'   => wp_create_nonce( 'login_me_now_generate_onetime_link_nonce' ),
			'plugin_installer_nonce' => wp_create_nonce( 'updates' ),
			'free_vs_pro_link'       => admin_url( 'admin.php?page=' . self::$plugin_slug . '&path=free-vs-pro' ),
			'plugin_installed_text'  => __( 'Installed', 'login-me-now' ),
			'plugin_activating_text' => __( 'Activating', 'login-me-now' ),
			'plugin_activated_text'  => __( 'Activated', 'login-me-now' ),
			'plugin_activate_text'   => __( 'Activate', 'login-me-now' ),
			'generating_token_text'  => __( 'Generating Magic Number...', 'login-me-now' ),
			'upgrade_notice'         => true,
		);

		$this->settings_app_scripts( apply_filters( 'login_me_now_react_admin_localize', $localize ) );
	}

	/**
	 * Get plugin status
	 *
	 * @since 0.94
	 *
	 * @param  string $plugin_init_file Plguin init file.
	 * @return mixed
	 */
	public static function get_plugin_status( $plugin_init_file ) {

		$installed_plugins = get_plugins();

		if ( ! isset( $installed_plugins[$plugin_init_file] ) ) {
			return 'install';
		} elseif ( is_plugin_active( $plugin_init_file ) ) {
			return 'activated';
		} else {
			return 'installed';
		}
	}

	/**
	 * Get Login Me Now's pro feature list.
	 *
	 * @since 0.94
	 * @return array
	 * @access public
	 */
	public static function get_pro_extensions() {
		return apply_filters(
			'login_me_now_feature_list',
			array(
				'email-magic-link' => array(
					'title' => __( 'Email Magic Link', 'login-me-now' ),
					'desc'  => __( "Sends a unique, time-limited link to the user's email address, which, when clicked, securely logs the user in without the need for a password.", 'login-me-now' ),
				),
				'facebook-login'   => array(
					'title' => __( 'Log in with Facebook', 'login-me-now' ),
					'desc'  => __( "Let user to login to WordPress dashboard using Facebook account. Users can sign into your website without having to create an account.", 'login-me-now' ),
				),
				'github-login'     => array(
					'title' => __( 'Log in with GitHub', 'login-me-now' ),
					'desc'  => __( "Let user to login to WordPress dashboard using Github account. Users can sign into your website without having to create an account.", 'login-me-now' ),
				),
				'otp-login'        => array(
					'title' => __( 'OTP Login', 'login-me-now' ),
					'desc'  => __( "Let user login to the WordPress dashboard using one time password ( OTP ) sent via SMS. The user don't have to know bad and weak passwords, sharing of credentials or reuse of the same password on multiple accounts.", 'login-me-now' ),
				),
			)
		);
	}

	/**
	 * Settings app scripts
	 *
	 * @since 0.94
	 * @param array $localize Variable names.
	 */
	public function settings_app_scripts( $localize ) {
		$handle            = 'login-me-now-admin-dashboard-app';
		$build_path        = LOGIN_ME_NOW_ADMIN_DIR . 'assets/build/';
		$build_url         = LOGIN_ME_NOW_ADMIN_URL . 'assets/build/';
		$script_asset_path = $build_path . 'dashboard-app.asset.php';

		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$script_info = file_exists( $script_asset_path ) ? include $script_asset_path : array(
			'dependencies' => array(),
			'version'      => LOGIN_ME_NOW_VERSION,
		);
		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort

		$script_dep = array_merge( $script_info['dependencies'], array( 'updates', 'wp-hooks' ) );

		wp_register_script(
			$handle,
			$build_url . 'dashboard-app.js',
			$script_dep,
			$script_info['version'],
			true
		);

		wp_register_style(
			$handle,
			$build_url . 'dashboard-app.css',
			array(),
			LOGIN_ME_NOW_VERSION
		);

		wp_register_style(
			'login-me-now-admin-google-fonts',
			'https://fonts.googleapis.com/css2?family=Inter:wght@200&display=swap',
			array(),
			LOGIN_ME_NOW_VERSION
		);

		wp_enqueue_script( $handle );

		wp_set_script_translations( $handle, 'login-me-now' );

		wp_enqueue_style( 'login-me-now-admin-google-fonts' );
		wp_enqueue_style( $handle );

		wp_style_add_data( $handle, 'rtl', 'replace' );

		wp_localize_script( $handle, 'lmn_admin', $localize );
	}

	/**
	 *  Add footer link.
	 *
	 * @since 0.94
	 */
	public function admin_footer_link() {
		echo '<span id="footer-thankyou"> Thank you for using <span class="focus:text-astra-hover active:text-astra-hover hover:text-lmn-hover"> ' . esc_attr( __( 'Login Me Now', 'login-me-now' ) ) . '.</span></span>';
	}
}

Menu::get_instance();
