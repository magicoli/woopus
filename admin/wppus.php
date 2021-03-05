<?php
/**
* Settings
*/

if ( ! defined( 'WPINC' ) ) die;

function WooPUS_query($params, $endpoint='wppus-license-api', $server="") {
  if(!$server) $server = get_option('WooPUS_pus_url');

  $url = "$server/$endpoint/"; // Replace domain.com with the domain where WP Plugin Update Server is installed.

  $response = wp_remote_post(
    $url,
    array(
      'method'      => 'POST',
      'timeout'     => 45,
      'redirection' => 5,
      'httpversion' => '1.0',
      'blocking'    => true,
      'headers'     => array(),
      'body'        => $params,
      'cookies'     => array(),
    )
  );

  if ( is_wp_error( $response ) ) {
    echo esc_html( sprintf( __( 'Something went wrong: %s', 'text-domain' ), $response->get_error_message() ) );
    return;
  } else {

    if ( '200' === $response->code ) {
      $data         = wp_remote_retrieve_body( $response );
      $decoded_data = json_decode( $data );
    } else {
      $data         = wp_remote_retrieve_body( $response );
      $decoded_data = json_decode( $data );
      // Handle failure with $decoded_data
      // new WPSE_224485_Message( "Wrong, I say wrong.", 'error' );
      // if ( $decoded_data->result != "success" ) {
      //   // add_action( 'admin_notices', function() {
      //   echo '<div class="notice notice-error is-dismissible">';
      //   echo "<h2>" . print_r( $decoded_data->message, true ) . "</h2>";
      //   foreach($decoded_data->errors as $error) {
      //     echo "<p>$error</p>";
      //   }
      //   echo "</div>";
      //   // } );
      // }
    }
  }
  return $decoded_data;
}

## API Doc https://github.com/froger-me/wp-plugin-update-server/blob/master/licenses.md
##
function WooPUS_license_get_info($key) { // return licence object
  $params = array(
  'action'      => 'check',        // Action to perform when calling the License API (required)
  'license_key' => $key, // The key of the license to check (required)
  );
  return WooPUS_query($params, 'wppus-license-api');
}
// $licence_object = WooPUS_license_get_info( get_option( 'license_key_woopus' ) );

function WooPUS_license_read($key) { // return licence object
  $params = array(
  'action'       => 'read',         // Action to perform when calling the License API (required)
  // 'id'           => '99',           // The id of the license to read (optional if license_key is provided)
  'license_key'  => $key, // The key of the license to read (optional if id is provided)
  'api_auth_key' => get_option('WooPUS_pus_api_key'),       // The Private API Authentication Key (required)
  );
  return WooPUS_query($params, 'wppus-license-api');
}
// $licence_object = WooPUS_license_read( get_option( 'license_key_woopus' ) );

function WooPUS_license_add($key, $email, $package_slug, $args=[] ) { // return licence object
  // $defaults=array(
  //   'expiration' => 365,
  // );
  $params = array(
  'action'              => 'add',           // Action to perform when calling the License API (required)
  'license_key'         => $key,  // The key of the license to add (required)
  'max_allowed_domains' => '1',             // The maximum number of domains allowed to use the license - minimum 1 (required)
  // 'allowed_domains'     => array(           // Domains currently allowed to use the license (optional)
  //   'domain1.example.com',
  //   'domain2.example.com',
  // ),
  'status'              => 'pending',       // The status of the license - one of pending, activated, deactivated, blocked, expired (required)
  // 'owner_name'          => 'Test Owner',    // The full name of the owner of the license (optional)
  'email'               => $email, // The email registered with the license (required)
  // 'company_name'        => 'Test Company',  // The company of the owner of the license (optional)
  // 'txn_id'              => '#111111111',    // If applicable, the transaction identifier associated to the purchase of the license (optional)
  'date_created'        => date( 'Y-m-d' ),    // Creation date of the license - YYYY-MM-DD  (required)
  // 'date_renewed'        => '2099-12-015',   // Date of the last time the license was renewed -\n YYYY-MM-DD (optional)
  // 'date_expiry'         => '2099-12-31',    // Expiry date of the license - YYY-MM-DD - if omitted, no expiry (optional)
  'package_slug'        => $package_slug,  // The package slug - only alphanumeric characters and dashes are allowed (required)
  'package_type'        => 'plugin',        // Type of package the license is for - one of plugin, theme (required)
  'api_auth_key' => get_option('WooPUS_pus_api_key'),       // The Private API Authentication Key (required)
  );
  $params = array_merge($params, $args);
  $result = WooPUS_query($params, 'wppus-license-api');
  return $result;
}
// $licence_object = WooPUS_license_add( 'test-' . uniqid(), 'user@example.com', 'dummy-package' );

// echo "<pre>WooPUS_license_add\n" . print_r( $data, true ) . "</pre>";

function WooPUSbrowse() {
  $license_query = array(
  'relationship' => 'AND',          // Relationship of the criteria when provided - 'AND or 'OR' - default 'AND' (optional)
  // 'limit'        => '10',           // Limit the number of results - default 10 (optional)
  // 'offset'       => '0',            // Results offset - default 0 (optional)
  'order_by'     => 'date_created', // Order of the license records returned - default 'date_created' (optional)
  'criteria'     => array(          // Criteria to filter the license records - accepts multiple values - if omitted, the result is not filtered (optional)
  array(
  'field'    => 'field',    // Field to filter by - see the list of accepted license fields below (required)
  'value'    => 'value',    // Value of the field to filter by - format depends on the operator (required)
  'operator' => 'operator'  // Comparison operator - see the list of accepted operators below (required)
  ),
  array(
  'field'    => 'field',    // Field to filter by - see the list of accepted license fields below (required)
  'value'    => 'value',    // Value of the field to filter by - format depends on the operator (required)
  'operator' => 'operator'  // Comparison operator - see the list of accepted operators below (required)
  ),
  // ...                           // More criteria...
  ),
  );
}
