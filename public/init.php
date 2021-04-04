<?php if ( ! defined( 'WPINC' ) ) die;

if ( defined( 'WOOPUS_PLUGIN' ) ) {
  if ( ! defined( 'WOOPUS_SLUG' ) ) define('WOOPUS_SLUG', dirname ( WOOPUS_PLUGIN ) );
} else {
  if ( ! defined( 'WOOPUS_SLUG' ) ) define('WOOPUS_SLUG', 'woopus' );
  define('WOOPUS_PLUGIN', WOOPUS_SLUG . "/" . WOOPUS_SLUG . ".php" );
}

function woopus_load_textdomain() {
	$textdomain = "woopus";
	load_plugin_textdomain( $textdomain, false, dirname( dirname(plugin_basename( __FILE__ )) ) . '/languages/' );
}
woopus_load_textdomain();

// require_once dirname(__DIR__) . '/vendor/autoload.php';

$plugin_data = get_file_data(WP_PLUGIN_DIR . "/" . WOOPUS_PLUGIN, array(
  'Name' => 'Plugin Name',
  'PluginURI' => 'Plugin URI',
  'Version' => 'Version',
  'Description' => 'Description',
  'Author' => 'Author',
  'AuthorURI' => 'Author URI',
  'TextDomain' => 'Text Domain',
  'DomainPath' => 'Domain Path',
  'Network' => 'Network',
));

if ( ! defined( 'WOOPUS_PLUGIN_NAME' ) ) define('WOOPUS_PLUGIN_NAME', $plugin_data['Name'] );
if ( ! defined( 'WOOPUS_PLUGIN_FILE' ) ) define('WOOPUS_PLUGIN_FILE', WOOPUS_SLUG . "/" . WOOPUS_SLUG . ".php" );
if ( ! defined( 'WOOPUS_SHORTNAME' ) ) define('WOOPUS_SHORTNAME', preg_replace('/ - .*/', '', WOOPUS_PLUGIN_NAME ) );
if ( ! defined( 'WOOPUS_PLUGIN_URI' ) ) define('WOOPUS_PLUGIN_URI', $plugin_data['PluginURI'] );
if ( ! defined( 'WOOPUS_VERSION' ) ) define('WOOPUS_VERSION', $plugin_data['Version'] );
if ( ! defined( 'WOOPUS_AUTHOR_NAME' ) ) define('WOOPUS_AUTHOR_NAME', $plugin_data['Author'] );
if ( ! defined( 'WOOPUS_TXDOM' ) ) define('WOOPUS_TXDOM', ($plugin_data['TextDomain']) ? $plugin_data['TextDomain'] : WOOPUS_SLUG );
if ( ! defined( 'WOOPUS_DATA_SLUG' ) ) define('WOOPUS_DATA_SLUG', sanitize_title(WOOPUS_PLUGIN_NAME) );
if ( ! defined( 'WOOPUS_STORE_LINK' ) ) define('WOOPUS_STORE_LINK', "<a href=" . WOOPUS_PLUGIN_URI . " target=_blank>" . WOOPUS_AUTHOR_NAME . "</a>");

// require_once __DIR__ . '/functions.php';
// require_once __DIR__ . '/post-types.php';
// require_once __DIR__ . '/fields.php';
// require_once __DIR__ . '/blocks.php';
// require_once __DIR__ . '/shortcodes.php';
// require_once __DIR__ . '/widgets.php';
// include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
// if ( is_plugin_active( 'woocommerce/woocommerce.php') ) {
// 	require_once __DIR__ . '/woocommerce.php';
// }

if(get_option('woopus_rewrite_rules') || get_option('woopus_rewrite_version') != WOOPUS_VERSION) {
  wp_cache_flush();
  add_action('init', 'flush_rewrite_rules');
	update_option('woopus_rewrite_rules', false);
  update_option('woopus_rewrite_version', WOOPUS_VERSION);
  // woopus_admin_notice( 'Rewrite rules flushed' );
}

add_action( 'wp_enqueue_scripts', function() {
  wp_enqueue_style( WOOPUS_SLUG . '-main', plugin_dir_url( __FILE__ ) . 'css/main.css', array(), WOOPUS_VERSION );
} );

// add_action( 'rwmb_enqueue_scripts', function() {
//     wp_enqueue_style( WOOPUS_SLUG . '-metabox', plugin_dir_url( __FILE__ ) . 'css/metabox.css', array(), WOOPUS_VERSION );
// } );
//
// add_action( 'enqueue_block_editor_assets', function() {
//   wp_enqueue_style( WOOPUS_SLUG . '-metabox', plugin_dir_url( __FILE__ ) . 'css/metabox.css', array(), WOOPUS_VERSION );
// } );
