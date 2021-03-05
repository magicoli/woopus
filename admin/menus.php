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
    'WooPUS', // $page_title
    'WooPUS', // menu_title
    'manage_woocommerce', // capability
    'woopus', // menu_slug
    'WooPUS_display_settings_page', // callable function
    //   '', // position
  );
}
add_action('admin_menu', 'WooPUS_register_settings_pages');
