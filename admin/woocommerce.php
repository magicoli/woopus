<?php
// add_filter( 'woocommerce_product_tabs', 'woopus_new_product_tab' );
// function woopus_new_product_tab( $tabs ) {
// 	// Adds the new tab
// 	$tabs['test_tab'] = array(
// 		'title' 	=> __( 'New Product Tab', 'woocommerce' ),
// 		'priority' 	=> 50,
// 		'callback' 	=> 'woopus_new_product_tab_content'
// 	);
// 	return $tabs;
// }
// function woopus_new_product_tab_content() {
// 	// The new tab content
// 	echo '<h2>New Product Tab</h2>';
// 	echo '<p>Here\'s your new product tab.</p>';
// }

function woopus_update_product_details($args) {
  // WP_Filesystem();

  $n="\n"; // for debug
  $product_id = $args['ID'];
  // wp_update_post( array('ID' => $product_id, 'post_excerpt' => $new_short_description ) );
  $product = get_post( $product_id );
  // echo "product " . print_r($product, true) . $n;
  $slug = $product->post_name;
  if(!$slug) return "no slug for $product_id";

  $packages_dir= WP_CONTENT_DIR . '/wppus/packages';

  $package_zip="$packages_dir/$slug.zip";
  $woopus_upload_dir=wp_upload_dir()['basedir'] . "/" . WOOPUS_SLUG;
  $woopus_upload_url=wp_upload_dir()['baseurl'] . "/" . WOOPUS_SLUG;

  if( ! file_exists($package_zip) ) return "cannot find $package_zip";

  $package_info_file=$woopus_upload_dir . "/$slug-info.txt";

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
    'package_zip',
  );
  $package_headers = array_combine($keys, $keys);

  // Get info from plugin file
  $checkfile=$woopus_upload_dir . "/$slug-check";
  $open = fopen($checkfile, "w+");
  $write = fwrite($open, $package_data_file);
  fclose($open);

  $meta = get_file_data( "zip://$package_zip#$slug/$slug.php", $package_headers);
  $meta = array_merge($meta, array_filter(get_file_data( "zip://$package_zip#$slug/readme.txt", $package_headers)));
  $meta['package_zip'] = $package_zip; // temporary for dev purpose, makes no sense

  $open = fopen($package_info_file, "w+");
  foreach($meta as $key => $value) {
    if($value) fwrite($open, "$key: $value" . $n);
    // echo "$key: $value" . $n;
  }
  fwrite($open, $n);
  // $write = fwrite($open, $package_readme . $n);
  // fclose($open);

  $meta = get_file_data($package_info_file, $package_headers);

  // echo "data: " . print_r($meta, true) . $n;

  $package_readme = file_get_contents("zip://$package_zip#$slug/readme.txt");

  $Parsedown = new Parsedown();

  $content =  preg_split ( '/\n==\s*/' , $package_readme, -1, PREG_SPLIT_DELIM_CAPTURE );
  array_shift($content); // first part is garbage
  foreach ($content as $key => $raw) {
    $split = preg_split ( '/\s*==\s*\n/' , $raw );
    $meta_key = sanitize_title($split[0]);
    $section['title'] = $split[0];
    $section['content'] = preg_replace('/=(.*)=/', '<h3>$1</h3>', "\n" . $Parsedown->text($split[1]) . "\n");
    $sections[$meta_key] = $section;
    if(! $fullcontent ) $fullcontent .= $section['content'];
    else $fullcontent .= "<h2>" . $section['title'] . "</h2>" . $section['content'];
  }
  // echo "sections: " . print_r($sections, true) . $n;

  $update = array(
    'ID' => $product_id,
    'post_title' => $meta['Plugin Name'] . " - by " . $meta['Author'],
    'post_excerpt' => $meta['Description'],
    // 'post_content' => 'ANd now: ' . $sections['description']['content'],
    'post_content' => '<div class="">' . $fullcontent . '</div>',
  );
  wp_update_post( $update );
  update_post_meta( $product_id, WOOPUS_SLUG . '_data', $meta );
  update_post_meta( $product_id, WOOPUS_SLUG . '_sections', $sections );

  // foreach ($sections as $meta_key => $section) {
  //   echo "$meta_key";
  //   echo "</pre>";
  //   echo "<h2>" . $section['title'] . "</h2>";
  //   echo $section['content'];
  //   echo "<pre>";
  // }
  return "success";
}
