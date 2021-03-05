<?php
if ( ! defined( 'WPINC' ) ) die;

function wppuswci_register_settings() {
  if ( ! current_user_can( 'manage_options' ) ) {
    $readonly=true;
  }

  // if(!get_option('license_key_wppus-woocommerce-integration')) {
  //   require_once(ABSPATH . '/wp-admin/includes/plugin.php');
  //   $PluginURI = get_plugin_data(plugin_dir_path(__DIR__) . '/wppus-woocommerce-integration.php', $markup = true, $translate = true )['PluginURI'];
  //   $description = sprintf(__('Register on %s to get a license key', 'wppus-wci'), '<a href="$PluginURI" target=_blank>' . $PluginURI . '</a>');
  // }

  wppuswci_settings_add_option( 'license_key_wppus-woocommerce-integration', "", array(
  	'name' => __('License key', 'wppus-wci'),
  	'description' => sprintf(__('Contact %s to get a license key', 'wppus-wci'), '<a href="https://magiiic.com/" target=_blank>Magiiic</a>'),
    'readonly' => true,
  ));
  wppuswci_settings_add_option( 'wppuswci_pus_url', '', array(
    'category' => __('Update server', 'wppus-wci'),
  	'name' => __('Update server URL', 'wppus-wci'),
    'default' => get_home_url(),
  ));

  wppuswci_settings_add_option( 'wppuswci_pus_api_key', '', array(
  	'name' => __('Update server API Authentication Key', 'wppus-wci'),
  ));

  wppuswci_settings_add_option( 'wppuswci_lm_key', '', array(
  	'name' => __('License Manager Consumer key', 'wppus-wci'),
  ));
  wppuswci_settings_add_option( 'wppuswci_lm_secret', '', array(
  	'name' => __('License Manager Consumer secret', 'wppus-wci'),
  ));

  wppuswci_settings_add_option( 'wppuswci_debug', '', array(
    'category' => __('Debug', 'wppus-wci'),
    'type' => 'textarea',
  	'name' => __('Debug area', 'wppus-wci'),
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
  // new wppuswci_Notice( "This is something. " . uniqid() . ' ' . __FILE__, 'warning' );
  require(plugin_dir_path(__FILE__) . 'inc/settings-page.php');
  if($notices) {
    foreach ($notices as $notice) {
      $notice->display_admin_notice();
    }
  }
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
