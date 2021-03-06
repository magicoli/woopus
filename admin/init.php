<?php
/**
 * Settings
 *
 * Author: Olivier van Helden
 * Version: 0.0.1
 */

if ( ! defined( 'WPINC' ) ) die;

// Set constants. Only WOOPUS_SLUG should be changed, other values are fetched from plugin file
// Some of these might also need to be defined in js files
if ( ! defined( 'WOOPUS_SLUG' ) ) define('WOOPUS_SLUG', 'woopus' );
if ( ! defined( 'WOOPUS_DATA_PLUGIN' ) ) define('WOOPUS_DATA_PLUGIN', WOOPUS_SLUG . "/" . WOOPUS_SLUG . ".php" );

$plugin_data = get_file_data(WP_PLUGIN_DIR . "/" . WOOPUS_DATA_PLUGIN, array(
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
if ( ! defined( 'WOOPUS_SHORTNAME' ) ) define('WOOPUS_SHORTNAME', preg_replace('/ - .*/', '', WOOPUS_PLUGIN_NAME ) );
if ( ! defined( 'WOOPUS_PLUGIN_URI' ) ) define('WOOPUS_PLUGIN_URI', $plugin_data['PluginURI'] );
if ( ! defined( 'WOOPUS_AUTHOR_NAME' ) ) define('WOOPUS_AUTHOR_NAME', $plugin_data['Author'] );
if ( ! defined( 'WOOPUS_TXDOM' ) ) define('WOOPUS_TXDOM', ($plugin_data['TextDomain']) ? $plugin_data['TextDomain'] : WOOPUS_SLUG );
if ( ! defined( 'WOOPUS_DATA_SLUG' ) ) define('WOOPUS_DATA_SLUG', sanitize_title(WOOPUS_PLUGIN_NAME) );
if ( ! defined( 'WOOPUS_STORE_LINK' ) ) define('WOOPUS_STORE_LINK', "<a href=" . WOOPUS_PLUGIN_URI . " target=_blank>" . WOOPUS_AUTHOR_NAME . "</a>");

/* translators: %s is replaced by the name of the plugin, untranslated */
if ( ! defined( 'WOOPUS_REGISTER_TEXT' ) ) define('WOOPUS_REGISTER_TEXT', sprintf(__('Get a license key on %s website', WOOPUS_TXDOM), WOOPUS_STORE_LINK) );

require(plugin_dir_path(__FILE__) . 'dependencies.php');
require(plugin_dir_path(__FILE__) . 'menus.php');
require(plugin_dir_path(__FILE__) . 'settings.php');
// require(plugin_dir_path(__FILE__) . 'woocommerce.php');

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
  $js = plugins_url(WOOPUS_SLUG . '/js/wppus-hide-licence-warnings.js');
  wp_register_script( $handle, $js, array( 'wp-i18n', 'jquery' ) );
  // wp_set_script_translations( $handle, WOOPUS_TXDOM );
  wp_enqueue_script( $handle, $js );
  foreach ( [ 'WOOPUS_SLUG', 'WOOPUS_DATA_SLUG', 'WOOPUS_DATA_PLUGIN', 'WOOPUS_TXDOM', 'WOOPUS_REGISTER_TEXT' ] as $CONST ) {
    wp_add_inline_script( $handle, "const $CONST = '" . constant($CONST) . "';", 'before' );
  }
  wp_add_inline_script( $handle, "const WOOPUS_SHOW_HIDE = '" . __( 'Show/Hide License key', WOOPUS_TXDOM ) . "';", 'before' );
}
