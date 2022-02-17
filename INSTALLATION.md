## Installation

I assume you already have a running WP Plugin Update Server, so I won't detail here the steps to set it up and enable auto updates for your custom plugin, this is covered by their documentation
(https://github.com/froger-me/wp-plugin-update-server).

1. Download and install this plugin via WP interface or unzip to /wp-content/plugins/woopus
2. Get your "Private API Authentication Key" from "WP Plugin Update Server" admin menu > "Licenses" tab
3. Create a Generator in "License Manager" admin menu > "Generator"
4. Create product.
    * It's important that the permalink is the same as the plugin slug (if the zip name is your-plugin.zip, the slug is 'your-plugin'). The easiest way is to set the slug as title (it will be changed later).
    * add *your-plugin.zip* to the downloadable files of the product. By uploading it or setting the file url to https://your.server/wp-content/wppus/packages/your-plugin.zip
    * set License Manager options
    * Don't write anything in description and excerpt, it will be replaced
    * Save. The page gets updated with complete plugin info.

The Plugin Update Server could be on a different website than your WooCommerce website. However, in this case and as for now, you will have to upload the plugin .zip manually after every update. This should be adressed in a future update.

When you upload the plugin manually, WooPUS will try to fetch the data from the Plugin Update Server if it's on the same website. If it fails, it will use the data from the uploaded zip.

