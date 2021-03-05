<?php

add_action( 'admin_head', 'WooPUS_alter_license_notice', 99, 0 );
function WooPUS_alter_license_notice() {
  global $WooPUS_alter_license_form;
  if ( $WooPUS_alter_license_form['woopus'] ) return;

  $slug = "woopus";
  $plugin_data = get_plugin_data(WP_PLUGIN_DIR . "/$slug/$slug.php");
  $plugin_data_slug = sanitize_title($plugin_data['Name']);
  $StoreURI = $plugin_data['PluginURI'];
  $StoreName = $plugin_data['AuthorName'];
  $RegisterText = sprintf(__('Get a license key on %s', 'woopus'), "<a href='$StoreURI' target=_blank>$StoreName</a>");
  ?>
  <style>
    /* .button.wppus-license-switch {
      margin-left: 5px;
      font-weight: normal;
    } */
    /* .hidden {
      display: none;
    }
    #wrap_license_woopus* {
      display: none;
    } */
  </style>
  <script type="text/javascript">
    jQuery(function($) {

      if ($('body').hasClass('wppus-license-form-alter-done-woopus')) {
        return;
      }

      var licenseRow = $( ".plugin-update-tr:has([data-package_slug='woopus'])" );
      var installRow = $( "[data-slug='<?= $plugin_data_slug ?>']");
      if(! installRow) return;

      $(".wrap-license[data-package_slug='woopus']").each( function( index, element ) {
        element = $(element);

        if (element.find('.current-license').html().length) {
          var buttonText = "<?php echo esc_html_e( 'License key', 'woopus' ); ?>";

          licenseRow.hide();
          installRow.find('div.row-actions').append(' <span> | <a class="wppus-license-switch woopus" href="#">' + buttonText + '</a></span>');
        } else {
          licenseRow.show();
          licenseRow.find('.wrap-license').append( "<p class='getlicense'><?=$RegisterText?></p>" );
        }
      });

      $('.wppus-license-switch.woopus').on('click', function(e) {
        e.preventDefault();
        licenseRow.toggle();
      });

      $('body').addClass('wppus-license-form-alter-done-woopus');
    });

  </script>
  <?php

  $WooPUS_alter_license_form['woopus'] = true;
}
