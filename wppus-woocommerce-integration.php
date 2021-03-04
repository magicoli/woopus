<?php
/**
 * Plugin Name:     WPPUS Woocommerce integration
 * Plugin URI:      https://git.magiiic.com/wordpress/wppus-woocommerce-integration
 * Description:     WooCommerce integration for WP Plugin Update Server
 * Author:          Magiiic
 * Author URI:      https://magiiic.com/
 * Text Domain:     wppus-wci
 * Domain Path:     /languages
 * Version:         0.0.3
 *
 * @package         WPPUS_Woocommerce_Integration
 */

// Your code starts here.

if ( ! defined( 'WPINC' ) ) die;

function wppuswci_load_textdomain() {
	$textdomain = 'wppus-wci';
	load_plugin_textdomain( $textdomain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
wppuswci_load_textdomain();

// function wppuswci_load_plugin_css() {
// 	wp_enqueue_style( 'cdt', plugin_dir_url( __FILE__ ) . 'style.css' );
// }
// add_action( 'wp_enqueue_scripts', 'wppuswci_load_plugin_css' );

if(is_admin()) {
	require_once __DIR__ . '/admin/init.php';
	// require_once __DIR__ . '/admin/wp-dependencies.php';
}

/** Enable plugin updates with license check **/
require_once plugin_dir_path( __FILE__ ) . 'lib/wp-package-updater/class-wp-package-updater.php';
$wppuswci_updater = new WP_Package_Updater(
	'https://magiiic.com',
	wp_normalize_path( __FILE__ ),
	wp_normalize_path( plugin_dir_path( __FILE__ ) ),
	true
);

// require_once __DIR__ . '/inc/post-types.php';
// require_once __DIR__ . '/inc/blocks.php';
// require_once __DIR__ . '/inc/shortcodes.php';
// require_once __DIR__ . '/inc/widgets.php';
