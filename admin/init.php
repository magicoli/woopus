<?php
/**
 * Settings
 *
 * Author: Olivier van Helden
 * Version: 0.0.1
 */

if ( ! defined( 'WPINC' ) ) die;

if ( ! defined( 'WOOPUS_SLUG' ) ) define('WOOPUS_SLUG', 'woopus' );
$plugin_data = get_plugin_data(WP_PLUGIN_DIR . "/" . WOOPUS_SLUG .'/' . WOOPUS_SLUG .'.php');
if ( ! defined( 'WOOPUS_PLUGIN_NAME' ) ) define('WOOPUS_PLUGIN_NAME', $plugin_data['Name'] );
if ( ! defined( 'WOOPUS_SHORTNAME' ) ) define('WOOPUS_SHORTNAME', preg_replace('/ - .*/', '', WOOPUS_PLUGIN_NAME ) );
if ( ! defined( 'WOOPUS_PLUGIN_URI' ) ) define('WOOPUS_PLUGIN_URI', $plugin_data['PluginURI'] );
if ( ! defined( 'WOOPUS_AUTHOR_NAME' ) ) define('WOOPUS_AUTHOR_NAME', $plugin_data['AuthorName'] );
if ( ! defined( 'WOOPUS_DATA_SLUG' ) ) define('WOOPUS_DATA_SLUG', sanitize_title(WOOPUS_PLUGIN_NAME) );
$link = "<a href=" . WOOPUS_PLUGIN_URI . " target=_blank>" . WOOPUS_AUTHOR_NAME . "</a>";
if ( ! defined( 'WOOPUS_REGISTER_TEXT' ) ) define('WOOPUS_REGISTER_TEXT', sprintf(__('Get a license key on %s', 'woopus'), $link) );

require_once(plugin_dir_path(__FILE__) . 'classes.php');

require(plugin_dir_path(__FILE__) . 'dependencies.php');
require(plugin_dir_path(__FILE__) . 'menus.php');
require(plugin_dir_path(__FILE__) . 'settings.php');
require(plugin_dir_path(__FILE__) . 'updater.php');
// require(plugin_dir_path(__FILE__) . 'wp-plugin-update-server-api.php');
// require(plugin_dir_path(__FILE__) . 'woocommerce.php');

// Redirect to settings page after activation
function WooPUS_activation_redirect( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        exit( wp_redirect( admin_url( 'admin.php?page=woopus' ) ) );
    }
}
add_action( 'activated_plugin', 'WooPUS_activation_redirect' );

if ( ! class_exists( 'WooPUS_Notice' ) ):
  class WooPUS_Notice {
    private $message;
    private $css_classes = array( 'notice' );

    public function __construct( $message='', $result='', $dismiss=true ) {
      if($message == '') return;

      $this->message = $message;

      if($result) $this->css_classes[] = "notice-$result";
      if($dismiss) $this->css_classes[] = "is-dismissible";

      // if( ! empty( $css_classes ) && is_array( $css_classes ) ) {
      // 	$this->css_classes = array_merge( $this->css_classes, $css_classes );
      // }

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
