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
define( 'DB_NAME', 'iaausuun_babalab' );

/** Database username */
define( 'DB_USER', 'iaausuun_babalab' );

/** Database password */
define( 'DB_PASSWORD', '^72^;i3g)q95' );

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
define( 'AUTH_KEY',         '~GHGv;u/eD[(6kEDJg,!A`Oh|f|,89m|Q*++w*@G|+w_Hd)REn6k~tIOw6x>=i6:' );
define( 'SECURE_AUTH_KEY',  'Kp][q;`bODqW/@Nr1T7|>fyjTfgU1M&J%qdvWD6Zpn_N!/s1/M|Wh/brC1E/}uZx' );
define( 'LOGGED_IN_KEY',    'PuAT>Llv.SzrP-o[<C<a0aPEie*0K65r^;-ch:#Nh;R1UG&D}bJZeI*sz{6y):Tu' );
define( 'NONCE_KEY',        '-^qS`<]x1ghP|M}-hyT2mC+$$MmO:8{tv}1SET|3_YmW]m{[kj$UR&20WQ+-jM`}' );
define( 'AUTH_SALT',        'GQs`j[H,&;$KF:<R8!$dG_HL||$r&wNqh!P&J|yufKcVdMUXxGtE/P/Nuw.}8x=B' );
define( 'SECURE_AUTH_SALT', 'FdqKF/wKzdSc2x^69SeN_7mefyB!@(g=/WsY5u7:nG}<aZ2:oIKb;vU53SjVh`m+' );
define( 'LOGGED_IN_SALT',   'n80wU[qVf!&_ymo~PZQo8AxsZtY9U&J `=Nb<Qr1G{BCuiWh$@PCcO 2`$bIz1c5' );
define( 'NONCE_SALT',       'gNp>N<r:qbS6#I?gjAVPqocYbZI(2UN#VT;B4<e;rZa*[O/+H;o8v?l/&kEa~A_}' );

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
