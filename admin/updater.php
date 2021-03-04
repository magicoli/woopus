<?php

add_action( 'admin_head', 'wppuswci_alter_license_notice', 99, 0 );
function wppuswci_alter_license_notice() {
  global $wppus_alter_license_form;

  if ( $wppus_alter_license_form['wppus-wci'] ) {

    return;
  }

  ?>
  <style>
    /* .button.wppus-license-switch {
      margin-left: 5px;
      font-weight: normal;
    } */
    /* .hidden {
      display: none;
    }
    #wrap_license_wppus-woocommerce-integration* {
      display: none;
    } */
  </style>
  <script type="text/javascript">
    jQuery(function($) {

      if ($('body').hasClass('wppus-license-form-alter-done-wppus-woocommerce-integration')) {
        return;
      }

      var licenseRow = $( ".plugin-update-tr:has([data-package_slug='wppus-wci'])" );
      var installRow = $( "[data-slug='wppus-wci']");
      if(! installRow) return;

      $(".wrap-license[data-package_slug='wppus-wci']").each( function( index, element ) {
        element = $(element);

        if (element.find('.current-license').html().length) {
          var buttonText = "<?php echo esc_html_e( 'License key', 'wppus-wci' ); ?>";

          licenseRow.hide();
          installRow.find('div.row-actions').append(' <span> | <a class="wppus-license-switch wppus-woocommerce-integration" href="#">' + buttonText + '</a></span>');
        } else {
          licenseRow.show();
          var RegisterText = "<?php echo sprintf(__('Register on %s to get a license key', 'wppus-wci'), '<a href=https://magiiic.com/wordpress/plugins/wppus-woocommerce-integration-by-magiiic/>Magiiic.com</a>'); ?>";
          licenseRow.find('.wrap-license').append( "<p class='getlicense'>" + RegisterText + "</p>" );
        }
      });

      $('.wppus-license-switch.wppus-woocommerce-integration').on('click', function(e) {
        e.preventDefault();
        licenseRow.toggle();
      });

      $('body').addClass('wppus-license-form-alter-done-wppus-woocommerce-integration');
    });

  </script>
  <?php

  $wppus_alter_license_form['wppus-wci'] = true;
}
