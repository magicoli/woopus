<?php
/**
 * Settings
 *
 * Author: Olivier van Helden
 * Version: 0.0.1
 */

if ( ! defined( 'WPINC' ) ) die;

global $wppuswci_options;
function wppuswci_register_settings_pages() {
  // default Settings submenu
  // add_options_page('wppus-wci', 'wppus-wci', 'manage_options', wppus-woocommerce-integration, 'wppuswci_display_settings_page');

  // Own menu
  add_menu_page(
    'WPPUS Woocommerce integration', // page title
    'WPPUS Woocommerce integration', // menu title
    'list_users', // capability
    'wppus-wci', // slug
    'wppuswci_display_settings_page', // callable function
    // plugin_dir_path(__FILE__) . 'options.php', // slug
    // null,	// callable function
    plugin_dir_url(__FILE__) . '../assets/svg-microphone-stand-20x20.svg', // plugin_dir_url(__FILE__) . '../assets/icon-24x24.jpg', // icon url
    2 // position
  );
  // add_submenu_page(
  //   'wppus-wci',  // parent_slug
  //   __(''wppus-wci' Settings', 'wppus-wci'), // $page_title
  //   __('Settings'), // menu_title
  //   'list_users',
  //   // 'manage_options', // capability
  //   'wppus-woocommerce-integration-settings', // menu_slug
  //   'wppuswci_display_settings_page', // callable function
  // );
  // add_submenu_page(
  //   '', // parent_slug
  //   '', // $page_title
  //   '', // menu_title
  //   '', // capability
  //   '', // menu_slug
  //   '', // callable function
  //   '', // position
  // );
}
add_action('admin_menu', 'wppuswci_register_settings_pages');
