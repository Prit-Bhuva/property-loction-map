<?php
class Property_Map_Plugin
{
    private $markers = array(); // Store markers data for the map

    private $google_maps_api_key; // Store the Google Maps API key


    /**
     * Constructor method for the class.
     *
     * This method initializes the Google Maps API key and registers necessary actions and shortcodes.
     *
     * @return void
     */
    public function __construct()
    {
        $this->google_maps_api_key = 'xxxxxxxxxxxxxxxxxxxxxx';

        add_action('wp_enqueue_scripts', array($this, 'enqueue_google_maps')); // Enqueue Google Maps script
        add_shortcode('property_map', array($this, 'property_map_shortcode')); // Shortcode to display mapS
        add_action('wp_head', array($this, 'property_map_script')); // Generate map script
        register_activation_hook(__FILE__, array($this, 'property_map_plugin_activate')); // Register activation hook

    }


    /**
     * Activates the property map plugin.
     *
     * @throws Exception If the main class cannot be included.
     * @return void
     */
    public function property_map_plugin_activate(): void
    {
        // do nothing
    }



    /**
     * Enqueues the Google Maps script with the specified API key.
     *
     * @return void
     */
    public function enqueue_google_maps(): void
    {
        if (is_page(1445)) :
            wp_enqueue_script(
                'google-maps',
                "https://maps.googleapis.com/maps/api/js?key={$this->google_maps_api_key}&callback=initPropertyMap",
                array(),
                null,
                true
            );
        endif;
    }

    /**
     * Shortcode to display the Google map
     *
     * @return string Html return
     */
    public function property_map_shortcode(): string
    {
        // Query to fetch properties
        $properties_query = new WP_Query(array(
            'post_type' => 'property',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ));

        // Collect location data for markers
        if ($properties_query->have_posts()) {
            while ($properties_query->have_posts()) {
                $properties_query->the_post();

                /*  $property_location = get_post_meta(get_the_ID(), 'property_location_google_map', true);

                // If location data exists, create marker information
                if ($property_location) {
                    $this->markers[] = array(
                        'lat' => $property_location['lat'],
                        'lng' => $property_location['lng'],
                        'title' => get_the_title(),
                        'permalink' => get_permalink(),
                    );
                } */

                // Fetch latitude and longitude from ACF group field 'coordinates'
                $property_coordinates = get_field('coordinates');
                $property_lat = isset($property_coordinates['latitude']) ? $property_coordinates['latitude'] : null;
                $property_lng = isset($property_coordinates['longitude']) ? $property_coordinates['longitude'] : null;

                // If location data exists, create marker information
                if (is_numeric($property_lat) && is_numeric($property_lng)) {
                    $this->markers[] = array(
                        'lat' => $property_lat,
                        'lng' => $property_lng,
                        'title' => get_the_title(),
                        'permalink' => get_permalink(),
                    );
                }
            }
            wp_reset_postdata();
        }

        // Return HTML for map container
        ob_start();
?>
        <div id="map" style="height: 400px;"></div>
    <?php
        return ob_get_clean();
    }


    /**
     * Generates a script for initializing a Google Map with markers.
     *
     * @return void
     */
    public function property_map_script(): void
    {
        //  Check if the current page is the one with ID 1445
        if (!is_page(1445)) {
            return;
        }

        $map_markers = json_encode($this->markers); // Encode markers data to JSON

        // Output JavaScript for initializing Google Map with markers
    ?>
        <script type="text/javascript">
            function initPropertyMap() {
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 10,
                    center: {
                        lat: 21.1702401,
                        lng: 72.8310607,
                    }
                });

                var customIcon = {
                    url: '/wp-content/uploads/2024/01/marker.png',
                };

                var markers = <?php echo $map_markers; ?>;

                var openInfoWindow = null;

                // Create markers on the map

                markers.forEach(function(markerInfo) {
                    // Check if lat and lng are valid numeric values
                    if (!isNaN(markerInfo.lat) && !isNaN(markerInfo.lng)) {
                        var marker = new google.maps.Marker({
                            position: {
                                lat: markerInfo.lat,
                                lng: markerInfo.lng
                            },
                            map: map,
                            title: markerInfo.title,
                            icon: customIcon,
                        });

                        var infowindow = new google.maps.InfoWindow({
                            content: '<a href="' + markerInfo.permalink + '" class="read-more-button"><h3>' + markerInfo.title + '</h3></a><a href="javascript:void(0);" class="read-more-button">Read More</a>',
                        });

                        // Show info window on marker mouseover
                        marker.addListener('mouseover', function() {
                            if (openInfoWindow) {
                                openInfoWindow.close();
                            }

                            infowindow.open(map, marker);
                            openInfoWindow = infowindow;
                        });

                        // Close info window on marker mouseout
                        /* marker.addListener('mouseout', function() {
                            openInfoWindow.close();
                        }); */
                    }
                });
            }

            window.addEventListener("load", initPropertyMap);
        </script>
<?php
    }
}
