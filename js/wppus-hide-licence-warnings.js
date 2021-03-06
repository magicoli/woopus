const { __, _x, _n, _nx } = wp.i18n;

jQuery(function($) {
  // Set same values as PHP constants defined in admin/init.php
  var WOOPUS_SLUG = 'woopus';
  if ($('body').hasClass('wppus-license-form-alter-done-' + WOOPUS_SLUG )) return;

  var WOOPUS_DATA_SLUG = WOOPUS_SLUG; // calculated from plugin name, might be different from slug
  var WOOPUS_DATA_PLUGIN = 'woopus/woopus.php';
  var WOOPUS_TXDOM = WOOPUS_SLUG; // translation text domain, might be different from slug

  var buttonText = __( 'Show/Hide License key', WOOPUS_TXDOM );

  var installRow = $( "[data-plugin='" + WOOPUS_DATA_PLUGIN + "']");
  var licenseRow = $( ".plugin-update-tr:has([data-package_slug='" + WOOPUS_SLUG + "'])" );

  if(! installRow) return;


  $(".wrap-license[data-package_slug='" + WOOPUS_SLUG + "']").each( function( index, element ) {
    element = $(element);

    if (element.find('.current-license').html().length) {
      licenseRow.hide();
      installRow.find('div.row-actions').append('<span> | <a class="wppus-license-switch ' + WOOPUS_SLUG + '" href="#">' + buttonText + '</a></span>');
    } else {
      licenseRow.show();
    }
  });

  $('.wppus-license-switch.' + WOOPUS_SLUG).on('click', function(e) {
    e.preventDefault();
    licenseRow.toggle();
  });

  $('body').addClass('wppus-license-form-alter-done-' + WOOPUS_SLUG);
});
