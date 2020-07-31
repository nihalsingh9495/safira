<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'safira' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '123456' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define( 'FS_METHOD', 'direct' );

ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'E2&M_dbn{9E#2rr%09%M6V+aA%ORk/#ul!zOaQ42{C++aSBJy``ZOq,fL`#qBKqG' );
define( 'SECURE_AUTH_KEY',  '4T~Awe7a`A%,T?xM_O&Og&]EOfPxZ%q#CWOinWA8KXNN:ys^LY!?fGaIuDs7fU!;' );
define( 'LOGGED_IN_KEY',    'K/64^crsJX}%RqX3lL6ZnsngcY$-Obt0Zmd?V=dFL=t87<Enm%1uo<@&8IbEKD&;' );
define( 'NONCE_KEY',        'FR5c-u5^_UCj14:^D{RhH)p]@3v]K{+VXWsR^JMzj4Y_,n9!kquD2LfF&*lg1$[?' );
define( 'AUTH_SALT',        'D=V0-|*8+&zqZgQnjDYoKV!FJ,,G(XN6R0UHuMo7T4ZiCm/pLg-gJ)y8~*(p%m@Z' );
define( 'SECURE_AUTH_SALT', '!iIt3Vx7a72p|wFxf4VV!1L(C,QVg/k[N/AuSl}X6k/._]vIpn<{AWT:8!%AuxuR' );
define( 'LOGGED_IN_SALT',   'qF{hHqI=z3^wiE(w.fs9!HAr%2i&00vc3SemHba9ifRr=E7>@4=. &X7g*yC/@T7' );
define( 'NONCE_SALT',       'i&;q~7[W7=u`q?NYVXM,x:pa[jiHqy(U>By)XmW.}W*crx]MGx#on<?bg(hh_~n~' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );
define('WP_MEMORY_LIMIT', '512M');


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
