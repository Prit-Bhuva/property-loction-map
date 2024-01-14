<?php

/**
 * Plugin Name: Property Location Map
 * Description: Display a Google Map with markers on property location. Use this shortcode to display map <strong>[property_map]</strong>.
 * Version: 1.0.0
 * Author: Prit Bhuva
 * Author URI: #
 */

defined('ABSPATH') || die("Invalid Request");

//define plugin path/url/file
define('MAP_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('MAP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MAP_PLUGIN_FILE', __FILE__);

// Include files
require_once plugin_dir_path(__FILE__) . 'includes/class-property-map-plugin.php';

// Initialize the plugin
new Property_Map_Plugin();
