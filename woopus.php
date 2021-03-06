<?php
/**
 * Plugin Name:     WooPUS - WooCommerce Integration for Plugin Update Server
 * Plugin URI:      https://magiiic.com/wordpress/woopus
 * Description:     WooCommerce integration for Plugin Update Server
 * Author:          Magiiic
 * Author URI:      https://magiiic.com/
 * Text Domain:     woopus
 * Domain Path:     /languages
 * Version:         0.1.7
 *
 * @package         WPPUS_Woocommerce_Integration
 *
 * Icon1x: https://git.magiiic.com/wordpress/woopus/-/raw/master/assets/icon-128x128.jpg
 * Icon2x: https://git.magiiic.com/wordpress/woopus/-/raw/master/assets/icon-256x256.jpg
 * BannerHigh: https://git.magiiic.com/wordpress/woopus/-/raw/master/assets/banner-1544x500.jpg
 * BannerLow: https://git.magiiic.com/wordpress/woopus/-/raw/master/assets/banner-772x250.jpg
 * Screenshot-1: https://git.magiiic.com/wordpress/woopus/-/raw/master/assets/screenshot-1.jpg
 */

// Your code starts here.

if ( ! defined( 'WPINC' ) ) die;

// function WooPUS_load_plugin_css() {
// 	wp_enqueue_style( 'cdt', plugin_dir_url( __FILE__ ) . 'style.css' );
// }
// add_action( 'wp_enqueue_scripts', 'WooPUS_load_plugin_css' );

if(is_admin()) {
	require_once __DIR__ . '/admin/init.php';
	// require_once __DIR__ . '/admin/wp-dependencies.php';
}
require_once __DIR__ . '/admin/license-manager-for-woocommerce.php';
require_once __DIR__ . '/admin/wp-plugin-update-server-api.php';

/** Enable plugin updates with license check **/
require_once plugin_dir_path( __FILE__ ) . 'lib/wp-package-updater/class-wp-package-updater.php';
$WooPUS_updater = new WP_Package_Updater(
	'https://magiiic.com',
	wp_normalize_path( __FILE__ ),
	wp_normalize_path( plugin_dir_path( __FILE__ ) ),
	true
);

// require_once __DIR__ . '/inc/post-types.php';
// require_once __DIR__ . '/inc/blocks.php';
// require_once __DIR__ . '/inc/shortcodes.php';
// require_once __DIR__ . '/inc/widgets.php';
function WooPUS_load_textdomain() {
	$textdomain = 'woopus';
	$result = load_plugin_textdomain( $textdomain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'WooPUS_load_textdomain' );
// WooPUS_load_textdomain();
