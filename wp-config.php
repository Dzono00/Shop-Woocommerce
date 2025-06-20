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
define('DB_NAME', 'ElektronskaProdavnica');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '9X8ph1a1kb)`=Hk#l|ON/d^34&vGQm2X2&nEy+w>X)TZ7U(*O7]Z6GS&1v?EF.-r');
define('SECURE_AUTH_KEY',  'TK3J)n0|;ZqzWv`-Xb_5Lb~a<%#Ju<Wd)yVAy2]SGPa}n67<*`ag{y>]kbxbG3qQ');
define('LOGGED_IN_KEY',    '4~*Hy9}0GsA[7;lb_2*{p/LF8$T=b{l&vuW!{%i1;vlpd-/O4KS$+R(sH,8>N[{!');
define('NONCE_KEY',        'lG1qYv`A>Nn/+W(Km2$><Jd[op*[ovdpGlsV&)a3]B)DbN?%~Pq[m@W:[T%~ATus');
define('AUTH_SALT',        'F7=,-+quAM W!m+EthEtHN^G9qz U7vvls_/rgceAnV[tRfB3D@.S3AfI}!Z_5=`');
define('SECURE_AUTH_SALT', '-%P^.T6K0,u8TX~u)2?6<Y~ZI1O0jF@!9;%QrwR#$QiuC]8Z-V5Mx_!PT<.jKYD7');
define('LOGGED_IN_SALT',   'jPUfYGfB*%-lrKtS`QHu*+%s](7h4vs9e0LG,jKoCm`t^>-gx:3k=d`87]0Te0Jy');
define('NONCE_SALT',       '68?!r|TV`lvbD8OIX eI L{D~q lEc(]VQZKG6gEE0DfYk8HWT!=~506Mob+oy<6');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
