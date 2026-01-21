<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'hoko_db');

/** Database username */
define('DB_USER', 'root');

/** Database password */
define('DB_PASSWORD', '');

/** Database hostname */
define('DB_HOST', 'MySQL-8.0');

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

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
define('AUTH_KEY',         'VR <Sh7~!FzWqgl$FM>1@F,+[*D:)|K(PK.#1Y~HA[GHFzYv>DCdV9^]m!hXF%(g');
define('SECURE_AUTH_KEY',  's~TP[RNOhA<;_wMr<s0t/$XetYMyl*V7S8UnxUe0g$kUbLn>d?wU/c6T^(5CxtSb');
define('LOGGED_IN_KEY',    ')&]YW<(@&xag&myLZCqoelT64[mwVheC]//b/W}@zN:U!}R2V#.PD={${_-Gv5@4');
define('NONCE_KEY',        '<kv0s$kw]UQ>?T_u<SjH&dN1:F;WUHw4x(^30!msO<$Ha%ym+4>:7]|h[L#Fl?Ew');
define('AUTH_SALT',        'm2f|zYj2#8oygygpS{DN_(?:</oB3 Acz)iWd/FYj[0m;28__7_.z_4BSmv{mYJ{');
define('SECURE_AUTH_SALT', 'mYaSgD`$Dv|l;&k9J|k%d;gZgAHixo+OWBohho<EYnKU#0q9E{M;5/:Up0g-g,=w');
define('LOGGED_IN_SALT',   'kcYXThI&Wfbh*|m43#88l* T;*)w,tYvwqO/yO8;&w1,H4Q9Myn1B)S]6tM>F8@B');
define('NONCE_SALT',       'UNek:J~o.nQn;GPApb0[57;fF?PzPj&51x1vU~-;O&Bq_t9PMpz4^m_NIe>zX >G');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define('WP_DEBUG', false);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (! defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
