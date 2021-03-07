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

  // $package_info_file=$woopus_upload_dir . "/$slug-info.txt";
  // $package_info_file="zip://$package_zip#$slug/readme.txt"

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
    'package_zip',
  );
  $package_headers = array_combine($keys, $keys);

  $meta_php = get_file_data( "zip://$package_zip#$slug/$slug.php", $package_headers);
  $meta_readme = get_file_data( "zip://$package_zip#$slug/readme.txt", $package_headers);
  $meta = array_merge($meta_php, array_filter($meta_readme));
  $meta['package_zip'] = $package_zip; // temporary for dev purpose, makes no sense

  $headers['banner'] = sprintf('<div class=banner><img src="%s" alt="%s banner"></div>', $meta['BannerLow'], $meta['Plugin Name']);
  $headers['logo'] = sprintf('<div class=logo><img src="%s" alt="%s logo"></div>', $meta['Icon1x'], $meta['Plugin Name']);
  $headers['title'] = sprintf('<div class=title><h1>%s</h1><div class=by>by %s</div></div>', $meta['Plugin Name'], $meta['Author']);

  $readme_text = file_get_contents("zip://$package_zip#$slug/readme.txt");

  $Parsedown = new Parsedown();

  $content =  preg_split ( '/\n==\s*/' , $readme_text, -1, PREG_SPLIT_DELIM_CAPTURE );
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
  $fullcontent = sprintf("<div class=headers>%s<div class=headerstitle style='display:flex'>%s<div>&nbsp;</div>%s</div>
  </div>", $headers['banner'], $headers['logo'],$headers['title'] ) . $fullcontent;

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

  return array('meta' => $meta,  'result' => 'success');
}
