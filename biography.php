<?php
function custom_theme_styles()
{
    wp_enqueue_style('typekit-fonts', 'https://use.typekit.net/zcb5mzu.css');
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital@1&display=swap', false);
    // wp_enqueue_style('nelagala-reset', 'https://cdn.jsdelivr.net/gh/akaienso/NELAGala-Design@migration/css/reset.css?' . time());
    // wp_enqueue_style('nelagala-style', 'https://cdn.jsdelivr.net/gh/akaienso/NELAGala-Design@migration/css/styles.css?' . time());
    // wp_enqueue_style('nelagala-navbar-style', 'https://cdn.jsdelivr.net/gh/akaienso/NELAGala-Design@migration/css/navbar.css?' . time());
    wp_enqueue_style('nelagala-reset', get_template_directory_uri() . '/inc/nelagala/css/reset.css?' . time());
    wp_enqueue_style('nelagala-style', get_template_directory_uri() . '/inc/nelagala/css/styles.css?' . time());
    wp_enqueue_style('nelagala-navbar-style', get_template_directory_uri() . '/inc/nelagala/css/navbar.css?' . time());
}
add_action('wp_enqueue_scripts', 'custom_theme_styles');
function custom_theme_scripts()
{
    wp_enqueue_script('nelagala-script', get_template_directory_uri() . '/inc/nelagala/js/script.js', array(), false, true);
}
add_action('wp_enqueue_scripts', 'custom_theme_scripts');
get_header();

$event_year = get_query_var('nelagala_year');

if (empty($event_year)) {
    $event_year = date('Y'); // Default to current year if not specified
}

// Query to fetch the event post by title, which is the year
$events = new WP_Query(array(
    'post_type' => 'nelagala_event',
    'title' => $event_year,
    'posts_per_page' => 1,
));

// Define sections and their corresponding ACF field keys
$sections = [
    '#about-the-event' => 'nelagala_event_title',
    '#event-roles' => 'roles',
    '#honorees' => 'honorees',
    '#tickets' => 'ticket_prices',
    //'#lodging' => 'lodging',
    '#sponsorships' => 'sponsorship_packages',
    '#advertising' => 'advertising_rates',
];


?>

<section class="sections">
    <div class="container">
        <div class="nelagala-event">

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <!-- SECTION: Navigation Sidebar -->
                    <nav class="event-navigation nav bg_shape">
                        <div class="burger-container">
                            <button id="burger" aria-label="Open navigation menu">
                                <span class="bar topBar"></span>
                                <span class="bar btmBar"></span>
                            </button>
                        </div>
                        <ul class="menu">
                            <?php foreach ($sections as $anchor => $field_key) : ?>
                                <?php
                                // Check if the field has content or rows (for repeaters)
                                $value = get_field($field_key);
                                if ((is_array($value) && !empty($value)) || (!is_array($value) && !empty($value))) : ?>
                                    <li class="menu-item"><a href="/nelagala/<?= $event_year ?><?= $anchor ?>"><?= ucfirst(str_replace('-', ' ', substr($anchor, 1))) ?></a></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                    <!-- !SECTION: Navigation Sidebar -->

            <?php endwhile;
            endif; ?>
            <?php

            // ACF field values
            $event_title = get_field('nelagala_event_title');
            $event_datetime = get_field('nelagala_event_datetime');
            $event_date = new DateTime($event_datetime);
            $display_date = $event_date->format('l, F j, Y'); // Use $event_date, not $datetime
            // This returns an array for Google Map field
            $event_location = get_field('nelagala_event_location');
            $lat = $event_location['lat'];
            $lng = $event_location['lng'];
            $google_api_key = get_field('google_api_key');
            $google_geocoding_api_key = get_field('google_geocoding_api_key');
            $response = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&key=$google_geocoding_api_key");

            // echo "<pre>" . $google_api_key . "</pre>";
            // echo "<pre>" . $google_geocoding_api_key . "</pre>";
            // echo "<pre>" . $response . "</pre>";

            // Decode the JSON response
            $data = json_decode($response);

            // Iterate over the address components to find the city and state
            $venue_city = "";
            $venue_state = "";
            foreach ($data->results[0]->address_components as $component) {
                if (in_array("locality", $component->types)) {
                    $venue_city = $component->long_name;
                }
                if (in_array("administrative_area_level_1", $component->types)) {
                    $venue_state = $component->short_name;
                }
            }
            $header_venue_message = get_field('header_venue_message');
            $venue_name = get_field('nelagala_venue_name');
            $promotional_video = get_field('promotional_video');
            $roles_section_headline = get_field('roles_section_headline');
            $roles_section_content = get_field('roles_section_content');
            $roles = get_field('roles');
            $honorees_section_headline = get_field('honorees_section_headline');
            $honorees_section_content = get_field('honorees_section_content');
            $honorees = get_field('honorees');
            $ticket_section_headline = get_field('ticket_section_headline');
            $ticket_section_top_content = get_field('ticket_section_top_content');
            $ticket_prices = get_field('ticket_prices');
            $lodging_section_headline = get_field('lodging_section_headline');
            $lodging_section_top_content = get_field('lodging_section_top_content');
            $lodging = get_field('lodging');
            $sponsorship_section_headline = get_field('sponsorship_section_headline');
            $sponsorship_section_top_content = get_field('sponsorship_section_top_content');
            $sponsorship_packages = get_field('sponsorship_packages');
            $advertising_section_headline = get_field('advertising_section_headline');
            $advertising_section_top_content = get_field('advertising_section_top_content');
            $advertising_rates = get_field('advertising_rates');

            // Initialize the array to track missing sections
            $missing_sections = [];
            ?>
            <main>
                <!--  SECTION: Event Header -->

                <header>
                    <h2>Save the Date</h2>
                    <div class="row">
                        <img class="header-img" src="<?php echo get_template_directory_uri() . '/inc/nelagala/img/sicilia.png'; ?>" alt="the Trinacria">
                        <div class="col divider">
                            <h3>Sons of Italy Foundation<sup>&reg;</sup> presents</h3>
                            <div class="title-wrap">
                                <p class="pre-title"><?php echo get_current_event_year_with_ordinal(); ?>
                                </p>
                                <h1 class="title"><?php echo  $event_title; ?></h1>
                                <p class="post-title">Gala</p>
                            </div>
                        </div>
                        <div class="col col-2">
                            <p class="text"><?php echo  $header_venue_message; ?></p>
                            <p class="col-2-title"><?php echo $venue_name; ?></p>
                            <p class="text"><?php echo  $venue_city; ?>, <?php echo  $venue_state; ?></p>
                        </div>
                    </div>
                    <div class="header-img-main">
                        <img src="<?php echo get_template_directory_uri() . '/inc/nelagala/img/header-main-img.jpg'; ?>" alt="Teatro Antico di Taormina">
                    </div>
                    <div class="header-footer-text"><? echo $display_date; ?> | More details coming soon!</div>
                </header>

                <!-- SECTION: Data dump of nelagala-participant field group -->
                <section id="Biography" class="participants">
                    <?php
                    $participant_slug = get_query_var('nelagala_participant_name');
                    $participant_posts = new WP_Query(array(
                        'name' => $participant_slug,
                        'post_type' => 'nelagala-participant',
                        'posts_per_page' => 1,
                    ));

                    if ($participant_posts->have_posts()) : while ($participant_posts->have_posts()) : $participant_posts->the_post(); ?>

                        <?php
                            $photo = get_field('photo');
                            $title = get_field('title');
                            $website = get_field('website');
                            $summary = get_field('summary');
                            ?>
                            <div class="row-container reverse">
                                <?php

                                if (!empty($photo)) {
                                    // Image variables
                                    $url = $photo['url'];
                                    $alt = $photo['alt'];

                                ?>
                                    <img src="<?php echo esc_url($url); ?>" alt="Photograph of <?php echo esc_attr($alt); ?>"><?php
                                                                                                                            } ?>

                                <div>
                                    <h3 class="full-name"><?php the_title(); ?></h3>
                                    <?php if (!empty($title)) { ?>
                                        <p class="personal-title"><?php echo esc_html($title); ?></p>
                                    <?php  } ?>
                                </div>

                            </div>
                        
                                <article>
                                    <?php 
                                    if (!empty($website)) { 
                                        echo the_content();
                                    }
                                    if (!empty($website)) { ?>
                                        <p class="participant-website"><a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener noreferrer">Learn more about <?php the_title(); ?></a></p>
                                    <?php  } ?>
                                </article>
                  
                    <?php endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>

                    <!-- !SECTION: Data dump of nelagala-participant field group -->
        </div>
    </div>
</section>

<?php get_footer(); ?>