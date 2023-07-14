<?php

/*
Plugin Name: Personizely
Description: Personizely Widgets and Website Personalization for Wordpress
Author URI: https://www.personizely.net?utm_source=wordpress&utm_medium=plugin
Version: 0.8
Author: Personizely
License: GPLv2 or later
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

define( 'PERSONIZELY_VERSION', '0.8' );
define( 'PERSONIZELY__MINIMUM_WP_VERSION', '3.1' );
define( 'PERSONIZELY__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PERSONIZELY__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PERSONIZELY_DELETE_LIMIT', 100000 );

define( 'PERSONIZELY_APP_HOST', 'app.personizely.net' );
define( 'PERSONIZELY_APP_URL', 'https://'. PERSONIZELY_APP_HOST );
define( 'PERSONIZELY_STATIC_HOST', 'static.personizely.net' );
define( 'PERSONIZELY_STATIC_URL', 'https://' . PERSONIZELY_STATIC_HOST );

require_once( PERSONIZELY__PLUGIN_DIR . 'class.personizely.php' );

add_action( 'init', array( 'Personizely', 'init' ) );

if ( is_admin() ) {
    require_once( PERSONIZELY__PLUGIN_DIR . 'class.personizely-admin.php' );
    add_action( 'init', array( 'Personizely_Admin', 'init' ) );
}
