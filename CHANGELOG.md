## Changelog

### Unreleased

### 1.3
* new readme sections displayed as product tabs
* cache and permalink rules flush on acivation and update

### 1.2.3
* added warning on generated content
* fix translations loading too late

### 1.2.2
* fix dependencies conflict with other plugins

### 1.2.1
* added option to synchronize procuct thumbnails with plugin icon (beta)

### 1.2
* new option to enable or disable product info sync, disabled by default
* added thumbnail update (with a default icon for now)
* fix thumbnail not reattached if file already exists
* fix thumbnail not duplicated animore when identical
* added default icons assets
* updated readme

### 1.1
* new metablock with Version, PHP and WP requirements, Author...

### 1.0.1
* fix settings page link in actions on plugins page

### 1.0
* new auto update post with plugin info
* added version to title
* requires product slug to be the same as plugin slug
* get the package zip from wppus/packages directory, otherwise the first valid plugin in downloadable files
* fix result still using previous dev test files as source
* removed obsolete empty files
* moved WooPus WC submenu down

### 0.1.7
* fix wppus-hide-licence-warnings.js conflict with other plugins using it

### 0.1.6
* fix get_plugin_data possible crash in init
* load js with wp queue instead of hardcoded

### 0.1.5
* cleanup test files
* exclude development and source files from .zip release

### 0.1.4
* added constants for common strings
* fixed WC menu name
* use same registration text in plugins page and woopus settings page
* use page slug as package_slug if attribute not set in product

### 0.1.2
* fix store url in registration notice

### 0.1.1
* added banners
* added second part to title

### 0.1
* kinda working, pretty cool
* settings page with recommended plugins
* admin notice function
