<?php
/**
 * Plugin Name:     WooPUS
 * Plugin URI:      https://git.magiiic.com/wordpress/woopus
 * Description:     WooCommerce integration for WP Plugin Update Server
 * Author:          Magiiic
 * Author URI:      https://magiiic.com/
 * Text Domain:     woopus
 * Domain Path:     /languages
 * Version:         0.0.3
 *
 * @package         WPPUS_Woocommerce_Integration
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
require_once __DIR__ . '/admin/wppus.php';

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
add_action( 'init', 'bndtls_load_textdomain' );
// WooPUS_load_textdomain();
