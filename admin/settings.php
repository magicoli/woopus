<?php
if ( ! defined( 'WPINC' ) ) die;

function WooPUS_register_settings() {
  if ( ! current_user_can( 'manage_options' ) ) {
    $readonly=true;
  }

  // if(!get_option('license_key_woopus')) {
  //   require_once(ABSPATH . '/wp-admin/includes/plugin.php');
  //   $PluginURI = get_plugin_data(plugin_dir_path(__DIR__) . '/woopus.php', $markup = true, $translate = true )['PluginURI'];
  //   $description = sprintf(__('Register on %s to get a license key', 'woopus'), '<a href="$PluginURI" target=_blank>' . $PluginURI . '</a>');
  // }

  WooPUS_settings_add_option( 'license_key_woopus', "", array(
  	'name' => __('License key', 'woopus'),
  	'description' => WOOPUS_REGISTER_TEXT,
    'readonly' => true,
  ));
  WooPUS_settings_add_option( 'WooPUS_pus_url', '', array(
    'category' => 'WP Plugin Update Server',
  	'name' => __('Update server URL', 'woopus'),
    'default' => get_home_url(),
  ));

  WooPUS_settings_add_option( 'WooPUS_pus_api_key', '', array(
    'category' => 'WP Plugin Update Server',
  	'name' => __('Update server API Authentication Key', 'woopus'),
  ));

  // WooPUS_settings_add_option( 'WooPUS_debug', '', array(
  //   'category' => 'Debug',
  //   'type' => 'textarea',
  // 	'name' => 'Debug area'),
  // ));
}
add_action( 'admin_init', 'WooPUS_register_settings' );

function WooPUS_display_settings_page()
{
  global $WooPUS_options;
	// if ( ! current_user_can( 'manage_options' ) ) {
	// 		return;
	// }
  // new WooPUS_Notice( "This is something. " . uniqid() . ' ' . __FILE__, 'warning' );
  require(plugin_dir_path(__FILE__) . 'inc/settings-page.php');
  if($notices) {
    foreach ($notices as $notice) {
      $notice->display_admin_notice();
    }
  }
}

function WooPUS_settings_link( $links ) {
	// Build and escape the URL.
	$url = esc_url( add_query_arg(
		'page',
		'woopus',
		get_admin_url() . 'options-general.php'
	) );
	// Create the link.
	$settings_link = "<a href='$url'>" . __( 'Settings', 'woopus') . '</a>';
	// Adds the link to the end of the array.
	array_push(
		$links,
		$settings_link
	);
	return $links;
} //end WooPUS_settings_link()
add_filter( 'plugin_action_links_woopus/woopus.php', 'WooPUS_settings_link' );

function WooPUS_settings_add_option($option, $default=NULL, $args) {
    global $WooPUS_options;
    if(empty($option)) return;

    if(empty($args['category'])) $args['category'] = 'default';
    if(empty($args['type'])) $args['type'] = 'string';
    if(empty($args['name'])) $args['name'] = $option;

    $WooPUS_options[$args['category']][$option]=$args;
    add_option( $option, $default);
    register_setting( 'woopus', $option, $args);
}
