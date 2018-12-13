<?php

/*

Plugin Name: WP AB stats

Plugin URI: http://github.com

Description: Un plugin de statistisque sous WordPress

Version: 0.1

Author: ANTIOCO Benjamin

Author URI: http://localhost.dom

License: GPL2

*/


if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'WPABSTATS_VERSION', '0.1' );
define( 'WPABSTATS__MINIMUM_WP_VERSION', '4.0' );
define( 'WPABSTATS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );


register_activation_hook( __FILE__, [ 'Wpabstats', 'pluginActivation' ] );
register_deactivation_hook( __FILE__, [ 'Wpabstats', 'pluginDesactivation' ] );

require_once( WPABSTATS__PLUGIN_DIR . 'class.wpabstats.php' );
require_once( WPABSTATS__PLUGIN_DIR . 'class.wpabstatsAdmin.php' );

require_once( WPABSTATS__PLUGIN_DIR . 'class.wpabstatsAjax.php' );
require_once( WPABSTATS__PLUGIN_DIR . 'class.wpabstatsWidget.php' );
require_once( WPABSTATS__PLUGIN_DIR . 'class.wpabstatsCollect.php' );

add_action( 'init', [ 'Wpabstats', 'init' ] );
add_action( 'init', [ 'wpabstatsAdmin', 'init' ] );

add_action( 'init', [ 'wpabstatsAjax', 'init' ] );
add_action( 'init', [ 'wpabstatsWidget', 'init' ] );
add_action( 'init', [ 'wpabstatsCollect', 'init' ] );


