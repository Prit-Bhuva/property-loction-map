# Property Location Map Plugin

## Description

The "Property Location Map" plugin is a custom WordPress plugin designed to display a Google Map with property locations using a shortcode. This plugin fetches the map location from a specified post type and displays all property locations on the map using markers.

## Installation

1. Upload the `property-location-map` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

## Usage

To display the Google Map with property locations, use the following shortcode in your WordPress post or page:

`[property_map]`

This will automatically fetch and display property locations on the map.

## Google Maps API Key

In order to use Google Maps functionality, you need to provide a valid Google Maps API key. Follow these steps:

1. Get your Google Maps API key from the [Google Cloud Console](https://console.cloud.google.com/).
2. Open the `includes/class-property-map-plugin.php` file inside the plugin folder. Locate the following line of code:

   ```php
   $this->google_maps_api_key = 'YOUR_API_KEY_HERE';
   ```

   Replace `'YOUR_API_KEY_HERE'` with your actual Google Maps API key.

3. Add your API key to the theme by modifying the `functions.php` file. add the following function and replace `'YOUR_API_KEY_HERE'` with your actual API key:

```php
/**
 * Google map API key for ACF
 */
function google_map_acf_api($api)
{
    $api['key'] = 'YOUR_API_KEY_HERE';
    return $api;
}
add_filter('acf/fields/google_map/api', 'google_map_acf_api');
```
