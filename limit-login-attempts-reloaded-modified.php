<?php
/*
Plugin Name: Limit Login Attempts Reloaded - Simplified
Description: Block brute force login attempts. Plugin modified by Parvus to remove all the extra stuff.
Author: Limit Login Attempts Reloaded (modified by Parvus.fi)
Author URI: https://parvus.fi
Text Domain: limit-login-attempts-reloaded-simplified
Version: 2.25.25

Copyright 2008 - 2012 Johan Eenfeldt, 2016 - 2023 Limit Login Attempts Reloaded
*/

if( !defined( 'ABSPATH' ) ) exit;

/***************************************************************************************
 * Constants
 **************************************************************************************/
define( 'LLAS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'LLAS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'LLAS_PLUGIN_FILE', __FILE__ );
define( 'LLAS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/***************************************************************************************
 * Different ways to get remote address: direct & behind proxy
 **************************************************************************************/
define( 'LLAS_DIRECT_ADDR', 'REMOTE_ADDR' );
define( 'LLAS_PROXY_ADDR', 'HTTP_X_FORWARDED_FOR' );

/* Notify value checked against these in limit_login_sanitize_variables() */
define( 'LLAS_LOCKOUT_NOTIFY_ALLOWED', 'log,email' );

$limit_login_my_error_shown = false; /* have we shown our stuff? */
$limit_login_just_lockedout = false; /* started this pageload??? */
$limit_login_nonempty_credentials = false; /* user and pwd nonempty */

if( file_exists( LLAS_PLUGIN_DIR . 'autoload.php' ) ) {

	require_once( LLAS_PLUGIN_DIR . 'autoload.php' );

	add_action( 'plugins_loaded', function() {
		(new LLARS\Core\LimitLoginAttempts());
	}, 9999 );
}
