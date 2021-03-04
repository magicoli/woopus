<?php
/**
 * Settings
 *
 * Author: Olivier van Helden
 * Version: 0.0.1
 */

if ( ! defined( 'WPINC' ) ) die;

function wppuswci_register_settings() {
  if ( ! current_user_can( 'manage_options' ) ) {
    $readonly=true;
  }

  wppuswci_settings_add_option( 'license_key_wppus-woocommerce-integration', "", array(
  	'name' => __('License key', 'wppus-wci'),
  	'description' => sprintf(__('Register on %s to get a license key', 'wppus-wci'), '<a href=https://magiiic.com/wordpress/plugins/wppus-woocommerce-integration-by-magiiic/>Magiiic.com</a>'),
    'readonly' => true,
  ));

  wppuswci_settings_add_option('wppuswci_coffee', "", array(
    'category' => __('Tweaks', 'wppus-wci'),
    'name' => __('Make coffee after login', 'wppus-wci'),
    'type'=>'boolean',
    'readonly' => $readonly,
  ));
}
add_action( 'admin_init', 'wppuswci_register_settings' );

function wppuswci_display_settings_page()
{
  global $wppuswci_options;
	// if ( ! current_user_can( 'manage_options' ) ) {
	// 		return;
	// }
	require(plugin_dir_path(__FILE__) . 'inc/settings-page.php');
}

function wppuswci_settings_link( $links ) {
	// Build and escape the URL.
	$url = esc_url( add_query_arg(
		'page',
		'wppus-wci',
		get_admin_url() . 'options-general.php'
	) );
	// Create the link.
	$settings_link = "<a href='$url'>" . __( 'Settings', 'wppus-wci') . '</a>';
	// Adds the link to the end of the array.
	array_push(
		$links,
		$settings_link
	);
	return $links;
} //end wppuswci_settings_link()
add_filter( 'plugin_action_links_wppus-woocommerce-integration/wppus-woocommerce-integration.php', 'wppuswci_settings_link' );

function wppuswci_settings_add_option($option, $default=NULL, $args) {
    global $wppuswci_options;
    if(empty($option)) return;

    if(empty($args['category'])) $args['category'] = 'default';
    if(empty($args['type'])) $args['type'] = 'string';
    if(empty($args['name'])) $args['name'] = $option;

    $wppuswci_options[$args['category']][$option]=$args;
    add_option( $option, $default);
    register_setting( 'wppuswci', $option, $args);
}
