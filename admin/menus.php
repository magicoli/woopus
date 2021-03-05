<?php
/**
 * Settings
 *
 * Author: Olivier van Helden
 * Version: 0.0.1
 */

if ( ! defined( 'WPINC' ) ) die;

global $WooPUS_options;
function WooPUS_register_settings_pages() {
  add_submenu_page(
    'woocommerce',  // parent_slug
    __('Plugin Update Server', 'woopus'), // $page_title
    __('Plugin Update Server', 'woopus'), // menu_title
    'manage_woocommerce', // capability
    'woopus', // menu_slug
    'WooPUS_display_settings_page', // callable function
    //   '', // position
  );
}
add_action('admin_menu', 'WooPUS_register_settings_pages');
