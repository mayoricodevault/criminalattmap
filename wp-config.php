<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'sotabail_wp373');

/** MySQL database username */
define('DB_USER', 'sotabail_wp373');

/** MySQL database password */
define('DB_PASSWORD', 'j!85JPS!99');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'ek3hpxdo0bnhemdlfjwx198cis7jibvqcqvucd2mehgslkctnw3wv2bskbn3y0rt');
define('SECURE_AUTH_KEY',  'vxydten8gcxwuaflq2bgvx552n1y6ukkxibfjx2cjp5q9zng01pncxsq79gx553t');
define('LOGGED_IN_KEY',    'aguqdpsoazd7tx9nqgqpybqto4hldpauv2iuqzqrm7d9qyxixk23gxckqsvoblkq');
define('NONCE_KEY',        'ltqz2etpjbqiskt7x6mzhobhmbvy3lp4g25bn5usih6mo3yl7gbpw6ccidiod7n8');
define('AUTH_SALT',        '2vg1t5i34u6rtqvdnqy8vdl5t0brl94bzh4qh3aopdudosgjnotqzt7eo6azmce3');
define('SECURE_AUTH_SALT', '9ntjtxqjpc6nuhc93rcschzzostlteyc0agbidrlxq34rexhcghbqh57ufpug9ml');
define('LOGGED_IN_SALT',   'tdopkijxgfjxphxock07mkwsxhupwd0ml5mux1ybo9t3ff9mgsnrppgu9skfcyc2');
define('NONCE_SALT',       'uhziwvgdp6fwprdd5jhxfgz9lnacw6yy9cbfotmecd5yrr7nowhpszqy482wvfs6');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
