<?php
if ( ! defined( 'WPINC' ) ) die;
?>
<h1><?php _e('Debug', 'wppus-wci'); ?></h1>
<?php

## API Doc https://github.com/froger-me/wp-plugin-update-server/blob/master/licenses.md
##

// $new_license = wppuswci_license_add('test-' . uniqid(), 'user@example.com', 'dummy-package', array(
//   'package_type' => 'plugin',
//   'owner_name' => 'Random Guy',
//   'txn_id' => uniqid(),
// ));
// $notices[] = new wppuswci_Notice( "<p><strong>$new_license->message</strong>: $new_license->package_slug $new_license->license_key $new_license->email $new_license->owner_name</p>", $new_license->result );
// // echo "<pre>\n" . print_r( $new_license, true ) . "</pre>";
