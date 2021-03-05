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
  add_submenu_page(
    'woocommerce',  // parent_slug
    __('WP Plugin Update Server', 'wppus-wci'), // $page_title
    __('WPPUS integration'), // menu_title
    'manage_woocommerce', // capability
    'wppus-woocommerce-integration-settings', // menu_slug
    'wppuswci_display_settings_page', // callable function
    //   '', // position
  );
}
add_action('admin_menu', 'wppuswci_register_settings_pages');
