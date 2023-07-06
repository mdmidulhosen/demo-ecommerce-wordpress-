<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'demo-ecommerce' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '#`$3[`=V[e^P{1O)w7`@|D|P%a8U8>(p:ggjLDz{!<b1V@v$$(EPJUO$sIVV7JlM' );
define( 'SECURE_AUTH_KEY',  '}brtqZ+QIddnY+]UNVi<WDMEZ,j[^=rp*~-dht{:=s$u%@8;[vfA&tr%4c&,M*G^' );
define( 'LOGGED_IN_KEY',    '`r3$4F5~}N~Oz%I9v:cK*b_}N)Q#e3@8Q~AUO/v$wj 7z8~hQ_~E($;@pSbRtb,u' );
define( 'NONCE_KEY',        'tW{b }o:b.vi,nWNW%l)OK2&oL{kEfnS23W|<;itq%Cmqpy_nQ-.`n#57Nz_6FC3' );
define( 'AUTH_SALT',        '3=`UmLC*;(LA`FrLgj_}:*~pBP/.M&jT<@c:{9^jDG=>t({_pCCxM/i.71L`:WR[' );
define( 'SECURE_AUTH_SALT', '7.`<~p&^OZtv!){e)[74HK6ApMe*#>Ub;q}/Rp-{65>W,O2D37c~Fl8QJ_HJ.[!^' );
define( 'LOGGED_IN_SALT',   '^%kaWUu0+mo}&g]x@hxPz~eg8lc}Tbe,6H>|gZi!83m.I<,#B5DO;ju;UD=*AN1;' );
define( 'NONCE_SALT',       '$|l[{7`AG`W6e_6/[3#dShM9VZAT5+,H#UZ-%AotWF}Na}D_,`(V/D9{7@|w_&Nn' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
