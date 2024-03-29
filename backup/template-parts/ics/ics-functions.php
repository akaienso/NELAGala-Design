<?php
require_once ABSPATH . 'wp-admin/includes/file.php';
include_once get_stylesheet_directory() . '/inc/nelagala/functions.php';

$event_year = get_query_var('nelagala_year', date('Y')); // Default to current year if not specified
$ng = fetch_nelagala_event_by_year($event_year);

function mmp_ics_download() {
    if ( is_singular('nelagala_event') && isset( $_GET['ics'] ) ) {

        // Include the ICS class
        include_once get_stylesheet_directory() . '/inc/nelagala/template-parts/ics/ics.php';

        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename=nelagala-2024.ics');

        // Assuming $ng_data is globally accessible or retrieve it here
        global $post;
        $event_year = get_query_var('nelagala_year', date('Y')); // Default to current year if not specified
        $ng = fetch_nelagala_event_by_year($event_year);
        $datetime = $ng['nelagala_event_datetime']; // Assuming this returns a start datetime
        $duration = 4 * 60 * 60; // 4 hours in seconds

        // Assuming datetime is in the format 'YYYY-MM-DD HH:MM:SS'
        $dtstart = date('Ymd\THis', strtotime($datetime));
        $dtend = date('Ymd\THis', strtotime($datetime) + $duration);

        $event_location = $ng['nelagala_event_location']; // an ACF Google Maps field
        if ($event_location) {
            // Loop over segments and construct HTML.
            $address = '';
            foreach( array('street_number', 'street_name', 'city', 'state', 'post_code', 'country') as $i => $k ) {
                if( isset( $event_location[ $k ] ) ) {
                    $address .= sprintf( '<span class="segment-%s">%s</span>, ', $k, $event_location[ $k ] );
                }
            }
            // Trim trailing comma.
            $address = trim( $address, ', ' );
        }

        $location = $address; // Adjust based on actual $venue_details structure
        $event_title = $ng_data['nelagala_event_title']; // Adjust if necessary

        // Create new instance of ICS class
        $ics = new ICS(array(
            'location' => $location,
            'dtstart' => $dtstart,
            'dtend' => $dtend,
            'summary' => $event_title,
            // 'description' => 'Description here', // Optional: Add if you have an event description field
        ));

        echo $ics->to_string();
        exit();
    }
}

add_action( 'template_redirect', 'mmp_ics_download' );
