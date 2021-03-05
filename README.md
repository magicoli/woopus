# WooPUS
* Contributors: magicoli69
* Donate link: https://paypal.me/magicoli
* Tags: wppus, plugin update server, WooCommerce, license keys
* Requires at least: 4.5
* Tested up to: 5.6.2
* Requires PHP: 5.6
* Requires PHP: 0.1.0
* License: GPLv2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html

WooCommerce integration for Plugin Update Server

## Description

Sell plugin licenses and synchronize the license keys with Plugin Update Server.

### Features

* Synchronize licenses bought with WP Plugin Update Server
* Synchronization is made via API, WP Plugin Update Server can be on the same
  or any website

### Requirements

* [License Manager for WooCommerce](https://woocommerce.com/products/woocommerce-subscriptions/)
* [WP Plugin Update Server](https://github.com/froger-me/wp-plugin-update-server)

Future developements might include alternatives.

## Installation

I assume you already have a running WP Plugin Update Server, so I won't detail
here the steps to set it up and enable auto updates for your custom plugin,
this is covered by their documentation
(https://github.com/froger-me/wp-plugin-update-server).

1. Download and install this plugin via WP interface
   or unzip to /wp-content/plugins/woopus
2. Get your "Private API Authentication Key" from "WP Plugin Update Server"
   admin menu > "Licenses" tab
3. Create a Generator in "License Manager" admin menu > "Generator"
4. Create a downloadable product and add your plugin in Downloadable files
  * set file name as PLUGIN-SLUG.zip
  * set file URL to https://YOUR-WC-SERVER/wp-content/wppus/packages/PLUGIN-SLUG.zip
  * set License Manager options
  * your product page slug must match PLUGIN-SLUG

The Plugin Update Server can be on a different website than your WooCommerce
website. However, in this case and as for now, you will have to upload the
plugin .zip manually in your product downloadable files instead of providing the
URL as shown above. This should be adressed in a future update.

## Frequently Asked Questions

There is only one question that matters, and the answer is 42.

## Changelog

### 0.1
* new name WooPUS
* added WooCommerce Subscriptions to recommended plugins
* added settings for Licence Manager key/secret pair
* added License Manager for WooCommerce in dependecies
* updated readme
* fixed translations not loaded, wrong callback
* moved setting menu as WooCommerce submenu
* removed demo setting
* removed License Manger API settings
* kinda working, pretty cool
* admin notice function

### 0.0.2
* added update libraries
* added admin files
* removed scaffold default test and package files

### 0.0.1
* Initial scaffold
