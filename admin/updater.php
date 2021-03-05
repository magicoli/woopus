<?php

add_action( 'admin_head', 'WooPUS_alter_license_notice', 99, 0 );
function WooPUS_alter_license_notice() {
  global $WooPUSalter_license_form;

  if ( $WooPUSalter_license_form['woopus'] ) {

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
      var installRow = $( "[data-slug='woopus']");
      if(! installRow) return;

      $(".wrap-license[data-package_slug='woopus']").each( function( index, element ) {
        element = $(element);

        if (element.find('.current-license').html().length) {
          var buttonText = "<?php echo esc_html_e( 'License key', 'woopus' ); ?>";

          licenseRow.hide();
          installRow.find('div.row-actions').append(' <span> | <a class="wppus-license-switch woopus" href="#">' + buttonText + '</a></span>');
        } else {
          licenseRow.show();
          var RegisterText = "<?php echo sprintf(__('Register on %s to get a license key', 'woopus'), '<a href=https://magiiic.com/wordpress/plugins/woopus-by-magiiic/>Magiiic.com</a>'); ?>";
          licenseRow.find('.wrap-license').append( "<p class='getlicense'>" + RegisterText + "</p>" );
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

  $WooPUSalter_license_form['woopus'] = true;
}
