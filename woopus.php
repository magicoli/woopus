<?php
/**
 * Plugin Name:     WooPUS - WooCommerce Integration for Plugin Update Server
 * Plugin URI:      https://magiiic.com/wordpress/woopus
 * Description:     WooCommerce integration for Plugin Update Server
 * Author:          Magiiic
 * Author URI:      https://magiiic.com/
 * Text Domain:     woopus
 * Domain Path:     /languages
 * Version:         1.3
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
if ( ! defined( 'WOOPUS_SLUG' ) ) define('WOOPUS_SLUG', 'woopus' );

// function WooPUS_load_textdomain() {
// 	$textdomain = 'woopus';
// 	$result = load_plugin_textdomain( $textdomain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
// }
// // add_action( 'init', 'WooPUS_load_textdomain' );
// WooPUS_load_textdomain();

require_once __DIR__ . '/public/init.php';
if(is_admin()) {
	require_once __DIR__ . '/admin/admin-init.php';
	// require_once __DIR__ . '/admin/wp-dependencies.php';
}
require_once __DIR__ . '/admin/license-manager-for-woocommerce.php';
require_once __DIR__ . '/admin/wp-plugin-update-server-api.php';
// require_once __DIR__ . '/inc/post-types.php';
// require_once __DIR__ . '/inc/blocks.php';
// require_once __DIR__ . '/inc/shortcodes.php';
// require_once __DIR__ . '/inc/widgets.php';
// if(!get_option('woopus_disable_templates'))
require_once __DIR__ . '/templates/templates.php';

/** Enable plugin updates with license check **/
require_once plugin_dir_path( __FILE__ ) . 'lib/wp-package-updater/class-wp-package-updater.php';
$WooPUS_updater = new WP_Package_Updater(
	'https://magiiic.com',
	wp_normalize_path( __FILE__ ),
	wp_normalize_path( plugin_dir_path( __FILE__ ) ),
	true
);

// function WooPUS_load_plugin_css() {
// 	wp_enqueue_style( WOOPUS_SLUG . '-global', plugin_dir_url( __FILE__ ) . 'css/global.css' ); #, array(), time() );
// }
// add_action( 'wp_enqueue_scripts', 'WooPUS_load_plugin_css' );

/**
 * The code that runs during plugin activation.
 */
function activate_woopus() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-activate.php';
	Woopus_Activate::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_woopus() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-deactivate.php';
	Woopus_Deactivate::deactivate();
}

register_activation_hook( __FILE__, 'activate_woopus' );
register_deactivation_hook( __FILE__, 'deactivate_woopus' );
