<?php

// global $wppuswci_recommended;

$wppuswci_dependencies = [
  [
    "name" =>  "WooCommerce",
    "host" =>  "wordpress",
    "slug" =>  "woocommerce/woocommerce.php",
    "uri" =>  "https://wordpress.org/plugins/woocommerce/",
    "optional" =>  false
  ],
  [
    "name" =>  "WP Plugin Update Server",
    "host" =>  "GitHub",
    "slug" =>  "wp-plugin-update-server/wp-plugin-update-server.php",
    "uri" =>  "https://github.com/froger-me/wp-plugin-update-server",
    "optional" =>  true
  ],
];

add_action( 'admin_init', 'wppuswci_dependencies_check' );

function wppuswci_dependencies_check() {
  global $wppuswci_dependencies;
  global $wppuswci_recommended;
  global $message, $required, $recommended;
  if(empty($wppuswci_dependencies)) return;
  // $unmet=array();
  $installed_plugins = get_plugins();
  foreach ($wppuswci_dependencies as $dependency) {
    $actions=array();
    $plugin_file=$dependency['slug'];
    $plugin = basename(dirname($plugin_file));
    if(is_plugin_active($plugin_file)) {
      $actions[]="<em>(active)</em>";
    } else {
      $action = 'install-plugin';
      if ( array_key_exists( $plugin_file, $installed_plugins )
      || in_array( $plugin_file, $installed_plugins, true )) {
        $activate_url = wp_nonce_url(
          admin_url('plugins.php?action=activate&plugin='.$plugin_file),
          'activate-plugin_'.$plugin_file
        );
        $actions[]="<span class=$action><a href='$activate_url'>" . __("Activate") . "</a></span>";
      } else {
        if($dependency['host'] == "wordpress") {
          $action_url=wp_nonce_url(
            add_query_arg(
              array(
                'action' => 'install-plugin',
                'plugin' => $plugin
              ),
              admin_url( 'update.php' )
            ),
            $action.'_'.$plugin
          );
          $target="";
          $actions[]="<a href='$action_url' $target>" . __("Install") . "</a>";
        } else {
          $action_url=$dependency['uri'];
          $target="target='_blank'";
          $actions[]="<a href='$action_url' $target>" . sprintf(__("Download from %s"), $dependency['host']) . "</a>";
        }
        // $actions[]="<span class=$action><a href='$activate_url'>" . __("Install") . "</a></span>";
      }
      if($dependency['optional']) $recommended[]=$dependency['name'] . sprintf(' (%s) ', __('recommended', 'wppus-wci')) . join(' ', $actions);
      else $required[]=$dependency['name'] . sprintf(' (%s) ', __('required', 'wppus-wci')) . join(' ', $actions);
      // $unmet[]="$plugin " . $dependency['name'] . ' ' . join(' ', $actions);
    }
    $wppuswci_recommended[$dependency['name']]=$dependency['name'] . " <span class=actions>" . join(' ', $actions) . "</span>";
  }
  if(!empty($required)) {
    add_action( 'admin_notices', function() {
      global $required, $recommended;
      echo '<div class="notice notice-error is-dismissible">';
      if(!empty($required)) echo "<h2>" . sprintf( __("%s requires these plugins:", 'wppus-wci') , 'WPPUS Woocommerce integration' ) . "</h2>"
      . "<ul><li><strong>" . join("</li><li>", $required) . "</strong></li></ul>";
      if(!empty($recommended)) echo "<ul><li>" . join("</li><li>", $recommended) . "</li></ul></p>";
      echo '</div>';
    } );
  }
  // if(count($unmet) > "0") {
  // }
  // return $unmet;
}
