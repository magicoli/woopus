<?php if ( ! defined( 'WPINC' ) ) die;

// Create custom tabs in product single pages
add_filter( 'woocommerce_product_tabs', 'woopus_product_tabs' );
function woopus_product_tabs( $tabs ) {
  global $post;

  $product_tabs = get_post_meta( $post->ID, WOOPUS_SLUG . '_product_tabs', true );
  if ( ! empty( $product_tabs ) ) {
    foreach($product_tabs as $key => $tab) {
      // $slug = sanitize_file_name($title);
      // echo "tab $key: ${tab['title']}" . "\n";
      $tabs[$key] = array(
        'title'    => __( $tab['title'], 'woocommerce' ),
        'priority' => 45,
        'content' => $tab['content'],
        'callback' => 'woopus_product_tab_content',
        // 'args' => [ 'tab_id' => $key, 'tab' => $tab ],
      );
    }
  }

  return $tabs;
}

// Add content to custom tab in product single pages (1)
function woopus_product_tab_content($args = array()) {
  global $post;

  $slug = $args;
  $product_tabs = get_post_meta( $post->ID, WOOPUS_SLUG . '_product_tabs', true );

  if ( ! empty( $product_tabs[$slug]['content'] ) ) {
    $content .= $product_tabs[$slug]['content'];
    echo apply_filters( 'the_content',   $content );
  }
}
