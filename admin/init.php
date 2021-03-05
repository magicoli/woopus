<?php
/**
 * Settings
 *
 * Author: Olivier van Helden
 * Version: 0.0.1
 */

if ( ! defined( 'WPINC' ) ) die;

require_once(plugin_dir_path(__FILE__) . 'classes.php');

require(plugin_dir_path(__FILE__) . 'dependencies.php');
require(plugin_dir_path(__FILE__) . 'menus.php');
require(plugin_dir_path(__FILE__) . 'settings.php');
require(plugin_dir_path(__FILE__) . 'updater.php');
// require(plugin_dir_path(__FILE__) . 'wppus.php');
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
