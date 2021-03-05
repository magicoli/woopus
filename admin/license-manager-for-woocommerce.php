<?php

// add_filter('lmfwc_add_license', 'WooPUS_lmfwc_add_license');
// function WooPUS_lmfwc_add_license($licenseKey, $licenseData = array()) {
//   $message = "<pre>";
//   $message .= "licenseKey " . print_r($licenseKey) . " \n";
//   $message .= "licenseData = " . print_r($licenseData, true);
//
//   $file = plugin_dir_path( __FILE__ ) . '/errors.txt';
//   $open = fopen( $file, "a" );
//   $write = fputs( $open, $message );
//   fclose( $open );
//
//   WooPUS_Notice($message);
// }

function WooPUS_order_status_changed( $order_id, $old_status, $new_status ){
  if( $new_status == "completed" ) {
    // $message = "Order $order_id $new_status\n";

    $order = wc_get_order( $order_id );
    // $message .= "Order: " . print_r($order, true) . "\n";
    if(! $order ) return;

    $order_email = $order->get_billing_email();
    $owner_name = trim($order->get_billing_first_name() . " " . $order->get_billing_last_name());
    $order_company = $order->get_billing_company();
    $txn_id = $order->get_transaction_id();

    if(! $txn_id ) $txn_id = "order $order_id";

    foreach ( $order->get_items() as $item_id => $item ) {
      $product_id   = $item->get_product_id(); // the Product id
      $product = wc_get_product( $product_id ); // Get the WC_Product Object
      $package_slug = $product->get_attribute( 'package_slug' );
      if(!$package_slug) $package_slug = $product->get_slug();
      $package_type = 'plugin'; // we don't handle themes for now but we will

      $license = \LicenseManagerForWooCommerce\Repositories\Resources\License::instance()->findBy(
        array(
          // 'hash'       => apply_filters('lmfwc_hash', 'YOUR-LICENSE-KEY'),
          'order_id'   => $order_id,
          'product_id' => $product_id,
        )
      );

      if ($license) {
        // $message .= "License key: " . $license->getDecryptedLicenseKey() . "\n";
        // $message .= "License: " . print_r($license, true) . "\n";
        $license_key = $license->getDecryptedLicenseKey();
        $date_expiry = $license->getExpiresAt();
        $add_license = WooPUS_license_add($license_key, $order_email, $package_slug, array(
          'package_type' => $package_type,
          'owner_name' => $owner_name,
          'txn_id' => $txn_id,
          'date_expiry' => $date_expiry,
        ));
        $message .= "add_license: $license_key, $order_email, $package_slug, " . print_r(array(
          'package_type' => $package_type,
          'owner_name' => $owner_name,
          'txn_id' => $txn_id,
          'date_expiry' => $date_expiry,
        ), true) . "\n";
        // $message .= "add_license response: " . print_r($add_license,  true) . "\n";
      }
    }

    // update_option('WooPUS_debug', get_option('WooPUS_debug') . $message . "\n\n" );

    // $order_id  = $order->get_id(); // Get the order ID
    // // $parent_id = $order->get_parent_id(); // Get the parent order ID (for subscriptions…)
    // //
    // $user_id   = $order->get_user_id(); // Get the costumer ID
    // $message .= "User id $user_id\n";
    //
    // $user      = $order->get_user(); // Get the WP_User object
    //
    // $order_status  = $order->get_status(); // Get the order status (see the conditional method has_status() below)
    // $currency      = $order->get_currency(); // Get the currency used
    // $payment_method = $order->get_payment_method(); // Get the payment method ID
    // $payment_title = $order->get_payment_method_title(); // Get the payment method title
    // $date_created  = $order->get_date_created(); // Get date created (WC_DateTime object)
    // $date_modified = $order->get_date_modified(); // Get date modified (WC_DateTime object)


    // $billing_country = $order->get_billing_country(); // Customer billing country
  }
}
add_action( 'woocommerce_order_status_changed', 'WooPUS_order_status_changed', 10, 3 );
