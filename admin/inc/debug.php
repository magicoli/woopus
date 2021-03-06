<h2>Debug</h2>
<p>Doc:
  <a target=wppus_doc href=https://github.com/froger-me/wp-plugin-update-server/blob/master/README.md>Readme</a>
  <a target=wppus_doc href=https://github.com/froger-me/wp-plugin-update-server/blob/master/licenses.md>licenses</a>
  <a target=wppus_doc href=https://github.com/froger-me/wp-plugin-update-server/blob/master/packages.md>packages</a>
<pre>
<?php
$n="\n";
$packages_dir="/home/magic/domains/monty.magiiic.com/www/w/wp-content/wppus/packages";
$package_slug="dummy-commercial-plugin";
$package_zip="$packages_dir/$package_slug.zip";
// echo "package_zip: $package_zip" . $n;
$woopus_upload_dir=wp_upload_dir()['basedir'] . "/" . WOOPUS_SLUG;
$woopus_upload_url=wp_upload_dir()['baseurl'] . "/" . WOOPUS_SLUG;
// echo "woopus_upload_dir: " . print_r($woopus_upload_dir, true) . $n;
// echo "woopus_upload_url: " . print_r($woopus_upload_url, true) . $n;

if(file_exists($package_zip)) {
  WP_Filesystem();
  // echo "found: " . $package_zip . $n;
  // $package_data_file = "zip://$package_zip#$package_slug/$package_slug.php");
  // $package_readme = file_get_contents("zip://$package_zip#$package_slug/readme.txt");
  $package_info_file=$woopus_upload_dir . "/$package_slug-info.txt";
  // echo "readme: " . print_r($package_data_file, true) . $n;

  $keys = array(
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
    'EmptyVar',
  );
  $package_headers = array_combine($keys, $keys);

  // Get info from plugin file
  $checkfile=$woopus_upload_dir . "/$package_slug-check";
  $open = fopen($checkfile, "w+");
  $write = fwrite($open, $package_data_file);
  fclose($open);

  $data = get_file_data( "zip://$package_zip#$package_slug/$package_slug.php", $package_headers);
  $data = array_merge($data, array_filter(get_file_data( "zip://$package_zip#$package_slug/readme.txt", $package_headers)));

  // echo "data: " . print_r($data, true) . $n;
  $open = fopen($package_info_file, "w+");
  foreach($data as $key => $value) {
    if($value) fwrite($open, "$key: $value" . $n);
    // echo "$key: $value" . $n;
  }
  fwrite($open, $n);
  // $write = fwrite($open, $package_readme . $n);
  // fclose($open);

  $data = get_file_data($package_info_file, $package_headers);

  echo "data: " . print_r($data, true) . $n;

} else {
  echo $package_zip . "not found" . $n;
}

?></pre>
