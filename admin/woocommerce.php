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

function woopus_Generate_Featured_Image( $source_url, $post_id, $dest='' ){
  if ( $dest ) $filename = basename($dest);
  else  $filename = basename($source_url);

  foreach(array(
    wp_get_upload_dir()['basedir'] . '/' . WOOPUS_SLUG,
    wp_get_upload_dir()['path'],
  ) as $try) {
    if(wp_mkdir_p($try)) {
      // $filename = basename($try);
      $upload_dir = $try;
      $file = "$upload_dir/$filename";
      break;
    }
  }
  if(!$file) return [ 'error' => 'no write access to destination dir' ];
  $filename=basename($file);

  $new_image_data = file_get_contents($source_url);
  if(!$new_image_data) return [ 'error' => 'could not read image' ];

  if(file_exists($file)) {
    $old_image_data = file_get_contents($source_url);
    if($old_image_data == $new_image_data)
    return [ 'success' => 'image not changed, nothing to do' ];
  }
  file_put_contents($file, $new_image_data);
  if(! file_exists($file)) return [ 'error' => 'file not created' ];

  $filetype = wp_check_filetype($filename, null );
  $attachment = array(
    // 'post_mime_type' => $filetype['type'],
    // 'post_title' => sanitize_file_name($filename),
    // 'post_content' => '',
    // 'post_status' => 'inherit'
    'guid'           => sanitize_file_name($filename),
    'post_mime_type' => $filetype['type'],
    'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
    'post_excerpt'   => sanitize_text_field( $caption ),
    'post_content'   => sanitize_text_field( $description ),
    'post_status'    => 'inherit'
  );
  $attachment_id = wp_insert_attachment( $attachment, $file, $post_id );
  if ( is_wp_error( $attachment_id ) ) return [ 'error' => 'wp_insert_attachment'];

  require_once(ABSPATH . 'wp-admin/includes/image.php');

  $attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
  wp_update_attachment_metadata( $attachment_id, $attachment_data );
  set_post_thumbnail( $post_id, $attachment_id );

  return [ 'success' => true, 'attachment_id' => $attachment_id ];
}

add_filter( 'wp_insert_post_data' , 'woopus_filter_add_plugin_info' , '98', 2 );
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
  // $fullcontent = sprintf("<div class=headers>%s<div class=headerstitle style='display:flex'>%s<div>&nbsp;</div>%s</div></div>", $headers['banner'], $headers['logo'],$headers['title'] ) . $fullcontent;
  $plugin_metas = array_filter(array(
    __('Version', WOOPUS_TXDOM ) => $meta['Version'],
    __('Last updated', WOOPUS_TXDOM ) => $meta['Last updated'],
    __('Active installations', WOOPUS_TXDOM ) => '',
    __('WordPress Version', WOOPUS_TXDOM ) => $meta['Requires at least'],
    __('Tested up to', WOOPUS_TXDOM ) => $meta['Tested up to'],
    __('PHP Version', WOOPUS_TXDOM ) => $meta['Requires PHP'],
    __('Languages', WOOPUS_TXDOM ) => $meta['Languages'],
    __('Tags', WOOPUS_TXDOM ) => $meta['Tags'],
    __('Author', WOOPUS_TXDOM ) => "<a href='" . $meta['Author URI'] ."' target=_blank>" . $meta['Author'] . "</a>",
    __('Donate link', WOOPUS_TXDOM ) => "<a href='" . $meta['Donate link'] ."' target=_blank>" . $meta['Donate link'] . "</a>",
  ) );
  foreach($plugin_metas as $key => $value ) {
    $metablock .= "<li>" . $key . " <strong>" . $value . "</strong></li>";
  }
  if ( "$metablock" ) {
    $metablock = "<h2 class='screen-reader-text'>Meta</h2><div class=plugin-meta><ul>" . $metablock . "</ul></div>";
  }

  $update = array(
    'ID' => $product_id,
    'post_title' => $meta['Plugin Name'] . " - by " . $meta['Author'],
    'post_excerpt' => $meta['Description'],
    // 'post_content' => 'ANd now: ' . $sections['description']['content'],
    'post_content' => '<div class="">' . $fullcontent . '</div>',
  );
  // $debug['product'] = $product;
  $data['post_title'] = $meta['Plugin Name'] . " " . $meta['Version'] . " - by " . $meta['Author'];
  $data['post_excerpt'] = $meta['Description'] . $metablock;
  $data['post_content'] = $fullcontent;

  $icons = array(
    WP_PLUGIN_DIR . "/" . dirname(WOOPUS_PLUGIN_FILE) . '/assets/default-plugin-icon-256x256.png',
    // "zip://$package_zip#$slug/readme.txt",
    "zip://$package_zip#$slug/assets/icon-256x256.png",
    "zip://$package_zip#$slug/assets/icon-256x256.jpg",
    $meta['Icon2x'],
    $meta['Icon1x'],
  );
  // $tmp_dir = get_temp_dir();
  foreach($icons as $source) {
    // $debug = woopus_Generate_Featured_Image( $source, $product_id );
    // if( $debug ) {
    //     $featured_image = $source;
    //     break;
    // }
    // if(file_exists($source)) {
    //   $featured_image = $source;
    //   break;
    // } else if (preg_match('!^zip:/!', $source )) {
    $ext = wp_check_filetype($source)['ext'];
    $basename = preg_replace('/^default-plugin-/', '', basename($source, ".$ext"));
    $dest = WP_CONTENT_DIR.'/'. WOOPUS_SLUG . "/assets/$slug-$basename.$ext";
    $tmp_icon = wp_get_upload_dir()['basedir'] . '/' . WOOPUS_SLUG . "/assets/$slug-$basename-tmp.$ext";
    $thisdebug['icon'] = $source;
    $thisdebug['dest'] = $dest;
    $thisdebug['tmp_icon'] = $tmp_icon;

    if(preg_match('/^zip:/', $source)) {
      // $debug['preg_last_error']=preg_last_error();
      $thisdebug['zip'] = "File not found or zip file, see later";
      // do something for zip
    } else if (file_exists($source)) {
      $thisdebug['no zip'] = "ok let's try";
      $try = woopus_Generate_Featured_Image( $source, $product_id, $dest );
      if($try) {
        $attachment_id = $try['attachment_id'];
        $featured_image = wp_get_attachment_url($attachment_id);
        break;
      }
    }
    // }
    $debug[]=$thisdebug;
    if($featured_image) break;
  }
  // if($featured_image)
  // unlink($tmp_icon);

  update_post_meta( $product_id, WOOPUS_SLUG . '_data', $meta );
  update_post_meta( $product_id, WOOPUS_SLUG . '_sections', $sections );
  if($attachment_id) {
    update_post_meta( $product_id, WOOPUS_SLUG . '_newthumb_id', $attachment_id );
    add_action( 'save_post', function() use ( $post_id, $attachment_id ) {
      $current_thumb_id = get_post_thumbnail_id($post_id);
      if($attachment_id && $current_thumb_id != $attachment_id)
      set_post_thumbnail( $post_id, $attachment_id );
    });
  }
  return $data;
}

function woopus_update_thumbnail( $post_id, $post, $update ) {
  $factory = new WC_Product_Factory();
  $product = $factory->get_product( $post_id );
  echo "<pre>";
  print_r(array(
    'post_id' => $post_id,
    'attachment_id ' . $attachment_id,
    'attachment_url ' . wp_get_attachment_url($attachment_id),
  ));
  die;
}
