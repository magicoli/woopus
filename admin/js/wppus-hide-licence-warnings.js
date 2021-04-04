jQuery(function($) {
  // Constants must be defined before via php or here
  //
  // WOOPUS_SLUG  your plugin slug (aka directory name)
  // WOOPUS_PLUGIN_FILE the plugin file, usually WOOPUS_SLUG/WOOPUS_SLUG.php
  // WOOPUS_SHOW_HIDE the show/hide link text, eg "Show/Hide License Key"
  // WOOPUS_REGISTER_TEXT the line added below empty license field, eg "Register your plugin at https://example.com"

  if ($('body').hasClass('wppus-license-form-alter-done-' + WOOPUS_SLUG )) return;

  var installRow = $( "[data-plugin='" + WOOPUS_PLUGIN_FILE + "']");
  var licenseRow = $( ".plugin-update-tr:has([data-package_slug='" + WOOPUS_SLUG + "'])" );
  var switchClass = WOOPUS_SLUG + '-license-switch';

  if(! installRow) return;

  $(".wrap-license[data-package_slug='" + WOOPUS_SLUG + "']").each( function( index, element ) {
    element = $(element);

    if (element.find('.current-license').html().length) {
      licenseRow.hide();
      installRow.find('div.row-actions').append('<span> | <a class="'  + switchClass + '" href="#">' + WOOPUS_SHOW_HIDE + '</a></span>');
    } else {
      licenseRow.show();
      licenseRow.find('.wrap-license').append( "<p class='getlicense'>" + WOOPUS_REGISTER_TEXT + "</p>" );
    }
  });

  $( '.' + switchClass ).on('click', function(e) {
    e.preventDefault();
    licenseRow.toggle();
  });

  $('body').addClass('wppus-license-form-alter-done-' + WOOPUS_SLUG);
});
