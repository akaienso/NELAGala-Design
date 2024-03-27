<?php
// Assuming $ng is populated with the event data for the current year as you've set up
$event_year = get_query_var('nelagala_year', date('Y')); // Default to current year if not specified
$ng = fetch_nelagala_event_by_year($event_year);

// ACF field values from $ng array
$full_event_switch = $ng['full_event_switch'] ?: false;
$is_demo_mode = !$full_event_switch;

// Sections to iterate through, based on your structure
$sections = [
    'about',
    'theme_sidebar',
    'promotional_video',
    'map',
    'roles',
    'honorees',
    'lodging',
    'tickets',
    'sponsorships',
    'advertising'
];

foreach ($sections as $section) {
    ${"show_$section"} = $ng["show_$section"] ?? false;
    ${$section . "_demo"} = $ng[$section . "_demo"] ?? false;
    ${"display_$section"} = ${"show_$section"} && (!$is_demo_mode || ($is_demo_mode && ${$section . "_demo"}));
}

if ($display_section) {
    // Load the section's content based on its visibility
    get_template_part('template-parts/section', $section, ['ng' => $ng]);
}
