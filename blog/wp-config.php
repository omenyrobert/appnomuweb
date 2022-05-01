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
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'appnomu_wp699' );

/** MySQL database username */
define( 'DB_USER', 'appnomu_wp699' );

/** MySQL database password */
define( 'DB_PASSWORD', '85E.Pdp5!S' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',         't8lowt1sdiiecbn2wzdrm8fndcfcnq30vv7xoh6qduipxsewcxcsr9geils4ziom' );
define( 'SECURE_AUTH_KEY',  'dxwuvmfpa2klczjpb7l8ritat6siczftcwfehwwszx96re4rtykvfnxhqgaxkrhg' );
define( 'LOGGED_IN_KEY',    'urd0funyjet1beugat15bjt6kryiawntzeddmmfvzuudnyfu1omfdulnykvf3myc' );
define( 'NONCE_KEY',        'ozbkiwdpohq9nx2twxr57lv5vo0xspv029ivbaqcmurvp68xypj3pm0qgddoyqdc' );
define( 'AUTH_SALT',        'mtqawpffgpz8hhx5penranrvjwz26o06vzugl5lqxfl99kdduls1htnuapc8oetl' );
define( 'SECURE_AUTH_SALT', 'xvwbw43lwxppicaxo9fxyqmycgzw3giuiejfymj4gnh38bpotofhyrf9fdbetsm9' );
define( 'LOGGED_IN_SALT',   '89ephklwh0sq3jnucnccebukjjohg4a0u7eahccgih0gn8btfizluwbh44lpppil' );
define( 'NONCE_SALT',       'h1scqjvrn2o3egg07jwe1rewpfwxh55rtn4ytz7afrot1qgvqomqdvbm91vgcrf4' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp1j_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
