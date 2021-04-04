<?php
/**
 * Settings
 *
 * Author: Olivier van Helden
 * Version: 0.0.1
 */

if ( ! defined( 'WPINC' ) ) die;

if ( ! defined( 'WOOPUS_PACKAGE_KEYS' ) ) define('WOOPUS_PACKAGE_KEYS', array(
    'Plugin Name',
    'Plugin URI',
    'Version',
    'Description',
    'Author',
    'Author URI',
    'Text Domain',
    'Domain Path',
    'Network',
    'Donate link',
    'Icon1x',
    'Icon2x',
    'BannerHigh',
    'BannerLow',
    'package_zip',
    'Last updated',
    'Requires at least',
    'Tested up to',
    'Requires PHP',
    'Languages',
    'Tags',
    'Donate link',
  )
);

/* translators: %s is replaced by the name of the plugin, untranslated */
if ( ! defined( 'WOOPUS_REGISTER_TEXT' ) ) define('WOOPUS_REGISTER_TEXT', sprintf(__('Get a license key on %s website', WOOPUS_TXDOM), WOOPUS_STORE_LINK) );

require(plugin_dir_path(__FILE__) . 'dependencies.php');
require(plugin_dir_path(__FILE__) . 'woocommerce.php');
require(plugin_dir_path(__FILE__) . 'menus.php');
require(plugin_dir_path(__FILE__) . 'settings.php');
// require(plugin_dir_path(__FILE__) . 'woocommerce.php');

require(plugin_dir_path(__DIR__) . 'lib/parsedown/Parsedown.php');

function WooPUS_load_admin_css() {
	wp_enqueue_style( WOOPUS_SLUG . '-admin', plugin_dir_url( __FILE__ ) . 'admin.css' );
  add_editor_style('custom-editor-style.css');
}
add_action( 'admin_enqueue_scripts', 'WooPUS_load_admin_css' );

function WooPUS_mce_css( $mce_css ) {
  if ( !empty( $mce_css ) )
    $mce_css .= ',';
    $mce_css .= plugins_url( 'admin-editor.css', __FILE__ );
    return $mce_css;
  }
add_filter( 'mce_css', 'WooPUS_mce_css' );

// Redirect to settings page after activation
function WooPUS_activation_redirect( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        exit( wp_redirect( admin_url( 'admin.php?page=woopus' ) ) );
    }
}
add_action( 'activated_plugin', 'WooPUS_activation_redirect' );

// Admin notifications
//
if ( ! class_exists( 'WooPUS_Notice' ) ):
  class WooPUS_Notice {
    private $message;
    private $css_classes = array( 'notice' );

    public function __construct( $message='', $result='', $dismiss=true ) {
      if($message == '') return;

      $this->message = $message;

      if($result) $this->css_classes[] = "notice-$result";
      if($dismiss) $this->css_classes[] = "is-dismissible";

      add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
    }

    public function display_admin_notice() {
      ?>
      <div class="<?php echo implode( ' ', $this->css_classes ); ?>">
        <p><?php echo $this->message; ?></p>
      </div>
      <?php
    }
  }
  $WooPUS_Notice = new WooPUS_Notice();
endif;

// Fix license key warning on plugins page if there is a license key
//
add_action( 'admin_head', 'woopus_alter_license_notice', 99, 0 );
function woopus_alter_license_notice() {
  // global $woopus_alter_license_form;
  // if ( $woopus_alter_license_form ) return;
  $handle = WOOPUS_SLUG . '-wppus-hide-licence-warnings';
  $js = plugin_dir_url( __FILE__ )  . '/js/wppus-hide-licence-warnings.js';
  wp_register_script( $handle, $js, array( 'wp-i18n', 'jquery' ) );
  // wp_set_script_translations( $handle, WOOPUS_TXDOM );
  wp_enqueue_script( $handle, $js );
  foreach ( [ 'WOOPUS_SLUG', 'WOOPUS_DATA_SLUG', 'WOOPUS_PLUGIN_FILE', 'WOOPUS_TXDOM', 'WOOPUS_REGISTER_TEXT' ] as $CONST ) {
    wp_add_inline_script( $handle, "const $CONST = '" . constant($CONST) . "';", 'before' );
  }
  wp_add_inline_script( $handle, "const WOOPUS_SHOW_HIDE = '" . __( 'Show/Hide License key', WOOPUS_TXDOM ) . "';", 'before' );
}
