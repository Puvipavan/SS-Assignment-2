<?php
/*
Plugin Name: Activate Plugins
Description: Activate specific plugins automatically.
Version: 1.0
Author: Puvipavan
*/

if ( !wp_installing() ) {
    include_once( WP_PLUGIN_DIR . '/email-subscribers/email-subscribers.php' );
    include_once( WP_PLUGIN_DIR . '/instawp-connect/instawp-connect.php' );
    include_once( WP_PLUGIN_DIR . '/wp-meta-seo/wp-meta-seo.php' );
    include_once( WP_PLUGIN_DIR . '/builderall-cheetah-for-wp/ba-cheetah.php' );
    include_once( WP_PLUGIN_DIR . '/capability-manager-enhanced/capsman-enhanced.php' );
    include_once( WP_PLUGIN_DIR . '/google-oauth/google-oauth.php' );
}
