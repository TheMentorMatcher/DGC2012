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
define('DB_NAME', 'MYSQL5_899204_wordpress');

/** MySQL database username */
define('DB_USER', 'thementorma');

/** MySQL database password */
define('DB_PASSWORD', 'Dgc2012');

/** MySQL hostname */
define('DB_HOST', 'mysql502.discountasp.net');

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
define('AUTH_KEY',         '.<jnp)Y-6S@IR1&`L^+~Zr|C3* ujJ!Tt~FgOz`Gr[_@kdRfmp;&.27jUuH)~<d&');
define('SECURE_AUTH_KEY',  ':x6}* 5!)FrL>gD<FK!E@vfY%wwM]^>SB`Kk4+dzB0*`vb{=i8(q*^E|@(gw6)zd');
define('LOGGED_IN_KEY',    '|5>~Tb.-iPuw[4rH3)3<)`8RP72cJiP1my5(!R Y^pT/s|4XCk*Oq.W*FS $BS^y');
define('NONCE_KEY',        '|:KvQsK6x !D!9R:vy*JniM=QN*wq|T:AY/}4-jhTypX:c-*>E|Om<2]S~1I>lrx');
define('AUTH_SALT',        'Ny?72alC%aEI1J#=VN})SJ7ae Rg/`|/F!*O8Rd#KVT,i8X.F)Hq~Q+vK#Kti=fg');
define('SECURE_AUTH_SALT', 'T5P0A$-&:J212khGctcjvF&=D/y@D}Yt!-gZlPkltOn]BHS2/Xf-/3q 9W1SdZ5j');
define('LOGGED_IN_SALT',   'U&@Dg^er)Bk&LJi|#I2 5B$0pQ )ua3Q++]p*%Lg_6^fC8SFb0YbYu|0Y%rHk4,K');
define('NONCE_SALT',       '*X~Ioj?_{Ie7/M_-d8 Xk._#Mph>WvMo2_EU=ONrBnyQOtIz4f|UTze%5GcD|IRk');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

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
