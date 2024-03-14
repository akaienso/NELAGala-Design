<?
function my_acf_init() {
    acf_update_setting('google_api_key', 'AIzaSyBGZLfom_9gzVfPI39FCQ1MHWGxNjxUqDg');
}
add_action('acf/init', 'my_acf_init');

function nelagala_add_query_vars($vars) {
    $vars[] = 'nelagala_year'; // For filtering events by year
    $vars[] = 'nelagala_info'; // For different sections like honorees, lodging, etc.
    $vars[] = 'nelagala_participant_name'; // For a specific participant's name
    return $vars;
}
add_filter('query_vars', 'nelagala_add_query_vars');

function nelagala_custom_rewrite_rules() {
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

function nelagala_template_include($template) {
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

function find_upcoming_event_year() {
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

function get_current_event_year_with_ordinal() {
    $start_year = 1990; // The year the first event took place
    $current_year = date('Y'); // Get the current year

    // Calculate the event number
    $event_number = $current_year - $start_year + 1;

    // Append ordinal suffix
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if (($event_number % 100) >= 11 && ($event_number % 100) <= 13)
       $ordinal_suffix = 'th';
    else
       $ordinal_suffix = $ends[$event_number % 10];

    return "The <span class='yr'>{$event_number}</span>{$ordinal_suffix} annual";
}
