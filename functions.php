<?
global $nelagala_event_post_id;

function custom_theme_styles()
{
    $uri = $_SERVER['REQUEST_URI'];

    // Check if the URI contains a specific segment indicating you're on a custom page
    if (strpos($uri, '/nelagala/') !== false) {    // Enqueue global styles
        wp_enqueue_style('typekit-fonts', 'https://use.typekit.net/zcb5mzu.css');
        wp_enqueue_style('nelagala-reset-style', get_template_directory_uri() . '/inc/nelagala/css/reset.min.css?' . time());
        wp_enqueue_style('nelagala-style', get_template_directory_uri() . '/inc/nelagala/css/styles.min.css?' . time());
    }
}
add_action('wp_enqueue_scripts', 'custom_theme_styles');


function custom_theme_scripts()
{

    // Get the current request URI
    $uri = $_SERVER['REQUEST_URI'];

    // Check if the URI contains a specific segment indicating you're on a custom page
    if (strpos($uri, '/nelagala/') !== false) {

        $google_api_key = get_field('google_api_key');
        $google_geocoding_api_key = get_field('google_geocoding_api_key');

        // Enqueue Google Maps API script
        wp_enqueue_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=' . $google_api_key . '&callback=initMap', array(), null, true);

        // Enqueue your custom script
        wp_enqueue_script('nelagala-script', get_template_directory_uri() . '/inc/nelagala/js/script.js', array('google-maps-api'), null, true);
        //     // Localize the script with the geocoding API key, if needed.
        if (!empty($google_api_key)) {
            wp_localize_script('nelagala-script', 'geocodingData', ['geocodingApiKey' => $google_geocoding_api_key]);
        }
    }
}

function display_event_date_countdown($event_date, $event_datetime) {
    $current_timestamp = time(); // Current Unix timestamp
    $event_timestamp = strtotime($event_datetime);
    $one_week_before_event = $event_timestamp - (7 * 24 * 60 * 60); // One week before event

    // Display "Save the Date" if more than one week before the event
    if ($current_timestamp < $one_week_before_event) {
        echo "<h2>Save the Date</h2>";
    } 
    // Otherwise, if within one week, display the countdown container
    else if ($current_timestamp < $event_timestamp) {
        echo "<div id='countdown' data-event-time='{$event_timestamp}'></div>";
    }
}

function add_async_defer_attribute($tag, $handle)
{
    // Add async and defer attributes to specific scripts
    if ('google-maps-api' === $handle) {
        return str_replace(' src', ' async defer src', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'add_async_defer_attribute', 10, 2);

add_action('wp_enqueue_scripts', 'custom_theme_scripts');

function my_acf_init()
{
    acf_update_setting('google_api_key', 'AIzaSyBGZLfom_9gzVfPI39FCQ1MHWGxNjxUqDg');
}
add_action('acf/init', 'my_acf_init');

function nelagala_add_query_vars($vars)
{
    $vars[] = 'nelagala_year'; // For filtering events by year
    $vars[] = 'nelagala_info'; // For different sections like honorees, lodging, etc.
    $vars[] = 'nelagala_participant_name'; // For a specific participant's name
    return $vars;
}
add_filter('query_vars', 'nelagala_add_query_vars');

function nelagala_custom_rewrite_rules()
{
    // Matches: /nelagala/2024/
    add_rewrite_rule('^nelagala/([0-9]{4})/?$', 'index.php?post_type=nelagala_event&nelagala_year=$matches[1]', 'top');

    // Matches: /nelagala/2024/honorees/, /nelagala/2024/lodging/, etc.
    add_rewrite_rule('^nelagala/([0-9]{4})/(honorees|lodging|sponsorships|advertising)/?$', 'index.php?post_type=nelagala_event&nelagala_year=$matches[1]&nelagala_info=$matches[2]', 'top');

    // Matches: /nelagala/2024/honorees/name-of-person
    add_rewrite_rule('^nelagala/([0-9]{4})/(honorees)/([^/]+)/?$', 'index.php?post_type=nelagala_event&nelagala_year=$matches[1]&nelagala_info=$matches[2]&nelagala_participant_name=$matches[3]', 'top');

    // Matches: /nelagala/first-last
    add_rewrite_rule('^nelagala/([a-z0-9-]+)/?$', 'index.php?post_type=nelagala_event&nelagala_participant_name=$matches[1]', 'top');
}
add_action('init', 'nelagala_custom_rewrite_rules');

function nelagala_template_include($template)
{
    global $nelagala_event_post_id;
    $nelagala_year = get_query_var('nelagala_year', false);

    if ($nelagala_year) {
        // Assuming 'event_year' is your custom field to query against.
        $events = new WP_Query(array(
            'post_type' => 'nelagala_event',
            'meta_query' => array(
                array(
                    'key' => 'event_year',
                    'value' => $nelagala_year,
                ),
            ),
            'posts_per_page' => 1,
        ));

        if ($events->have_posts()) {
            $events->the_post();
            $nelagala_event_post_id = get_the_ID(); // Successfully fetched the event post ID based on year.
        }

        wp_reset_postdata(); // Always good practice after custom queries.
    }
    if (!is_post_type_archive('nelagala_event') && !get_query_var('nelagala_year', false) && !get_query_var('nelagala_info', false) && !get_query_var('nelagala_participant_name', false)) {
        return $template;
    }

    $nelagala_year = get_query_var('nelagala_year', false);
    $upcomingEventYear = find_upcoming_event_year();
    $nelagala_info = get_query_var('nelagala_info', false);
    $nelagala_participant_name = get_query_var('nelagala_participant_name', false);

    if ($nelagala_participant_name) {
        // Biography page for a participant
        $new_template = locate_template(['inc/nelagala/biography.php']);
        if ('' != $new_template) {
            return $new_template;
        }
    }

    // Redirect to the base event URL if the requested year is the upcoming event year
    if ($nelagala_year && $nelagala_year == $upcomingEventYear) {
        wp_redirect(home_url('/nelagala'));
        exit;
    }

    if (!$nelagala_year) {
        // This is the base URL without a specific year, show the upcoming event landing page
        $new_template = locate_template(['inc/nelagala/landing-page.php']);
        if ('' != $new_template) {
            return $new_template;
        }
    } elseif ($nelagala_info) {
        // Handle specific sections of the event based on nelagala_info and nelagala_participant_name
        switch ($nelagala_info) {
            case 'roles':
            case 'honorees':
                if ($nelagala_participant_name) {
                    // Specific honoree's biography for a given year
                    $new_template = locate_template(['inc/nelagala/biography.php']);
                } else {
                    // List of honorees for a given year
                    $new_template = locate_template(['inc/nelagala/honorees.php']);
                }
                break;
            case 'lodging':
                // Lodging information for a given year
                $new_template = locate_template(['inc/nelagala/lodging.php']);
                break;
                // Add other cases as needed
        }

        if ('' != $new_template) {
            return $new_template;
        }
    } elseif ($nelagala_year) {
        // For a general year-specific event page, not targeting a specific section
        $new_template = locate_template(['inc/nelagala/landing-page.php']);
        if ('' != $new_template) {
            return $new_template;
        }
    }

    // Fallback to the default template if no specific nelagala template is set
    return $template;
}
add_filter('template_include', 'nelagala_template_include');

function find_upcoming_event_year()
{
    // Set up a query for nelagala_event posts ordered by date, ascending
    $args = array(
        'post_type' => 'nelagala_event',
        'posts_per_page' => 1, // We only need the closest future event
        'meta_key' => 'nelagala_event_datetime',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => 'nelagala_event_datetime',
                'value' => date('Y-m-d H:i:s'), // Current date and time
                'compare' => '>=', // Looking for dates in the future
                'type' => 'DATETIME'
            )
        )
    );
    $query = new WP_Query($args);

    // If we have posts, extract the year from the nelagala_event_datetime
    if ($query->have_posts()) {
        $query->the_post();
        $datetime = get_field('nelagala_event_datetime');
        wp_reset_postdata(); // Always reset postdata after custom queries

        // Assuming the date is stored in a format PHP can interpret, like 'Y-m-d H:i:s'
        $eventYear = date('Y', strtotime($datetime));
        return $eventYear;
    }

    // Return current year as fallback
    return date('Y');
}

function get_current_event_year_with_ordinal()
{
    $start_year = 1990; // The year the first event took place
    $current_year = date('Y'); // Get the current year

    // Calculate the event number
    $event_number = $current_year - $start_year + 1;

    // Append ordinal suffix
    $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
    if (($event_number % 100) >= 11 && ($event_number % 100) <= 13)
        $ordinal_suffix = 'th';
    else
        $ordinal_suffix = $ends[$event_number % 10];

    return "The <span class='yr'>{$event_number}</span>{$ordinal_suffix} annual";
}

function pass_acf_to_js()
{
    $current_year = date('Y'); // Use the current year
    $events = new WP_Query(array(
        'post_type' => 'nelagala_event',
        'year' => $current_year, // Assuming the post's publish year matches
        'posts_per_page' => 1,
    ));

    if ($events->have_posts()) {
        $events->the_post();
        $post_id = get_the_ID(); // Get the ID of the event post for the current year

        // Fetch the event location using ACF's get_field
        $event_location = get_field('nelagala_event_location', $post_id);
        $hotels = []; // Initialize an empty array for hotels

        // Check if there are lodging entries
        if (have_rows('lodging', $post_id)) {
            while (have_rows('lodging', $post_id)) {
                the_row();
                $hotel_name = get_sub_field('property');
                $hotel_location = get_sub_field('location'); // Assuming 'location' is the ACF Google Maps field

                // Append each hotel's information to the hotels array
                $hotels[] = [
                    'name' => $hotel_name,
                    'location' => [
                        'lat' => $hotel_location['lat'], // Extract latitude
                        'lng' => $hotel_location['lng'], // Extract longitude
                    ]
                ];
            }
        }

        // Localize the script with your data
        wp_localize_script('nelagala-script', 'eventData', array(
            'eventLocation' => $event_location,
            'hotels' => $hotels,
        ));
    }

    wp_reset_postdata();
}
add_action('wp_enqueue_scripts', 'pass_acf_to_js');


// function custom_document_title_parts($title) {
//     global $page_title; // Make sure this is the same variable used in your template

//     if (!empty($page_title)) {
//         $title['title'] = strip_tags($page_title); // Use strip_tags() to remove HTML tags like <sup>
//     }

//     return $title;
// }

add_filter('document_title_parts', function ($title) {
    if (is_post_type_archive('nelagala_event')) {
        $title['title'] = 'The National Education & Leadership Awards Gala'; // Change this to your desired title
    }
    return $title;
});
