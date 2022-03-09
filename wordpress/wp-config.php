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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'marmischlag' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'admin' );

/** Database hostname */
define( 'DB_HOST', 'db' );

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
define( 'AUTH_KEY',         '9^Lo*oW4:bF#iCgVn2IkkM0;)v8aJ13)5l,-D]:tFZE@%8x/*c j$cS]1X_dS>%7' );
define( 'SECURE_AUTH_KEY',  'E{.2TiNV{n(8%Zj@[3JTU6xlAm5n/jM3}^>w0v&bx`B}6Fr1v8$fmAv^u<%/UL7J' );
define( 'LOGGED_IN_KEY',    '>Rq])US{R7$/GThY+w_oo}y;oWyb~}l3^SR7@SvZZa(/ 2rr8!f6y6%_j= M*BRU' );
define( 'NONCE_KEY',        '8K|x|*285#dd$Ef49^+QZ*wq~8L*!bgy`t,)5/F:2u&ryjvo=Z|R`JA~vRmvQvu}' );
define( 'AUTH_SALT',        '8,En]hqtv# n,4s$VTEF]5J1H+>B  -U}Vf:7WtS/+0~ZjDOO={HXl#761V/g0~M' );
define( 'SECURE_AUTH_SALT', 'U~5VQnVYuW&UA8)#V,4H?.3X>u-4nbIV}G,mSm5#mbh]uvblsQRU|#4<=IZra-`x' );
define( 'LOGGED_IN_SALT',   '^?Xl1&}ul4G~n2I[l|`}JPeZ69Lud,0au78)r&_[PzCVPeHs7XS?n.Z}u*5h^JD{' );
define( 'NONCE_SALT',       'NhcGGq,v*!g0ehV,5im_ (!u>`q8JG6q5g&fIv<tTKwoaRVn}|+FAbXz&d%*?y=A' );

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
