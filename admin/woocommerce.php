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

add_filter( 'wp_insert_post_data' , 'woopus_filter_add_plugin_info' , '99', 2 );
// function woopus_filter_post_data( $data , $postarr ) {
//     // Change post title
//     $data['post_title'] .= '_suffix';
//     return $data;
// }

function woopus_filter_add_plugin_info($data , $postarr) {
  $product_id = $postarr['ID'];
  $factory = new WC_Product_Factory();
  $product = $factory->get_product( $product_id );

  if(!$product) return $data;
  $downloads = $product->get_downloads();
  if(!$downloads) return $data;

  $slug = $product->slug;
  $zipfile=$slug.'.zip';
  if(file_exists(WP_CONTENT_DIR.'/wppus/packages'.'/' . $zipfile))
  $package_zip = WP_CONTENT_DIR.'/wppus/packages'.'/' . $zipfile;
  else {
    // Loop through each downloadable file
    $upload_dir = wp_get_upload_dir()['basedir'];
    $upload_url = wp_get_upload_dir()['baseurl'];
    foreach( $downloads as $key => $value ) {
      $fileurl=$value['file'];
      if(preg_match('!'.$upload_url.'.*/' . $zipfile.'!', $fileurl)) {
        $package_zip=preg_replace('!'.$upload_url.'!', $upload_dir, $fileurl);
        // We could handle several packages in the same product but we won't
        break;
      }
    }
  }
  if(!$package_zip) return $data;

  $package_headers = array_combine(WOOPUS_PACKAGE_KEYS, WOOPUS_PACKAGE_KEYS);

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
  // $debug['product'] = $product;
  $data['post_title'] = $meta['Plugin Name'] . " - by " . $meta['Author'];
  $data['post_excerpt'] = $meta['Description'] . "<div><em>A plugin proudly provided by <b>Magiiic</b></em></div>";
  $data['post_content'] = $fullcontent;

  update_post_meta( $product_id, WOOPUS_SLUG . '_data', $meta );
  update_post_meta( $product_id, WOOPUS_SLUG . '_sections', $sections );
  return $data;
}
