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
define('START_TIME', microtime(true));

 /**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */

/**
 * Note: You can add define('WP_DEBUG_ON', true) to any of the config files and it will turn on here.
 * Also, if you are on localhost, it will automatically be on.
 */

define('WP_DEBUG', $_SERVER['SERVER_NAME'] == 'localhost' ? true : defined('WP_DEBUG_ON'));

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH'))
	define('ABSPATH', dirname(__FILE__) . '/');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'L5_]A#pyI`DM-V}|CGES>PL[9H*wKBXq1-sO,z@V_+|xSME<~ozKB-tDk=<eR:+/');
define('SECURE_AUTH_KEY',  'Gmt<:|tH@6|{/Y/`f[oFy2_`9=y1o{7^B-@_0|Gb!q18}[pn$ads-embBKJv,Lv,');
define('LOGGED_IN_KEY',    'Km@+jQ#=hQG0+WRZSNipS{-1@h7v@%:{LFR,W#<R bV3KPks] W)5Ah18d$Ik:%D');
define('NONCE_KEY',        '!9tH(}x]|llJ,auX82a6(h`((wWm<a+-cA~/D<Y+t9R5t+= /(@;/Zi.W_i]1C=:');
define('AUTH_SALT',        '!nulaL=To2d:>dhKV<hWh#u<2 )j,]38PFNa-hIVZe (:SoxrtfOqbq0-Mn,%z4E');
define('SECURE_AUTH_SALT', '7XxAakxsnQt+WB0}{R*30aNh2weoA83Qh^/7y8lK`-(_={U&qF,d[88o}q)z4.}Z');
define('LOGGED_IN_SALT',   '[Zb^&%l;:o[m|&qx]y[ms6Eg6!%;(QL7KlGoi[,`P[$V0!eF++J0-k@^G|Jc@aUH');
define('NONCE_SALT',       's+D17A-;+JsqoIoZa:)Vcd%tk*3~U%VyjE~+z%ZLtt1Im@wzfI>Z-K(gI[;6i?#}');

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

/** For security */
define('DISALLOW_FILE_EDIT', true);

/** SMTP Setup */
define('WPMS_ON', true);
define('WPMS_MAIL_FROM', 'support@ginsystem.com');
define('WPMS_MAIL_FROM_NAME', 'Gin System Support');
define('WPMS_MAILER', 'smtp'); // Possible values 'smtp', 'mail', or 'sendmail'
define('WPMS_SET_RETURN_PATH', 'false'); // Sets $phpmailer->Sender if true
define('WPMS_SMTP_HOST', 'smtp.sendgrid.net'); // The SMTP mail host
define('WPMS_SMTP_PORT', 25); // The SMTP server port number
define('WPMS_SSL', ''); // Possible values '', 'ssl', 'tls' - note TLS is not STARTTLS
define('WPMS_SMTP_AUTH', true); // True turns on SMTP authentication, false turns it off
define('WPMS_SMTP_USER', 'GinSystem'); // SMTP authentication username, only used if WPMS_SMTP_AUTH is true
define('WPMS_SMTP_PASS', 'smtpGin!!'); // SMTP authentication password, only used if WPMS_SMTP_AUTH is true

/**
 * Include necessary config files
 */

$files = array('s3_config', 'memcached_config', 'db_config');
foreach ($files as $file) {
	if (!file_exists(ABSPATH . ($f = $file . '.php'))) {
		die('Please create a ' . $f . ' file.');
	}
	require(ABSPATH . $f);
}

if (defined('USE_MEMCACHE_SESSIONS') && USE_MEMCACHE_SESSIONS) {
	ini_set("session.save_handler", "memcache");
	ini_set("session.save_path", "tcp://".MEMCACHE_SERVER_ADDR.":".MEMCACHE_SERVER_PORT);
}

/* That's all, stop editing! Happy blogging. */

/** Sets up WordPress vars and included files. */
if (!defined('NO_WP'))
	require_once(ABSPATH . 'wp-settings.php');
