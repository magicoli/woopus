<?php
/**
 * Settings
 *
 * Author: Olivier van Helden
 * Version: 0.0.1
 */

if ( ! defined( 'WPINC' ) ) die;

require(plugin_dir_path(__FILE__) . 'dependencies.php');
require(plugin_dir_path(__FILE__) . 'menus.php');
require(plugin_dir_path(__FILE__) . 'settings.php');
require(plugin_dir_path(__FILE__) . 'updater.php');
// require(plugin_dir_path(__FILE__) . 'woocommerce.php');

// Redirect to settings page after activation
function wppuswci_activation_redirect( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        exit( wp_redirect( admin_url( 'admin.php?page=wppus-woocommerce-integration' ) ) );
    }
}
add_action( 'activated_plugin', 'wppuswci_activation_redirect' );
