<?php
global $page_title;

$event_year = get_query_var('nelagala_year', date('Y')); // Default to current year if not specified

// Use the new function to fetch the event post ID
$event_post_id = fetch_nelagala_event_by_year($event_year);

if ($event_post_id) {
    // ACF field values
    $datetime = get_field('nelagala_event_datetime', $event_post_id);
    $event_title = get_field('nelagala_event_title', $event_post_id);
    $event_datetime = get_field('nelagala_event_datetime', $event_post_id);
    $event_date = new DateTime($event_datetime);
    $display_date = $event_date->format('l, F j, Y'); // Use $event_date, not $datetime
    // This returns an array for Google Map field
    $event_location = get_field('nelagala_event_location', $event_post_id);
    $lat = $event_location['lat'];
    $lng = $event_location['lng'];
    $google_api_key = get_field('google_api_key', $event_post_id);
    $google_geocoding_api_key = get_field('google_geocoding_api_key', $event_post_id);
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
    $header_venue_message = get_field('header_venue_message', $event_post_id);
    $venue_name = get_field('nelagala_venue_name', $event_post_id);
    $promotional_video = get_field('promotional_video', $event_post_id);
    $roles_section_headline = get_field('roles_section_headline', $event_post_id);
    $roles_section_content = get_field('roles_section_content', $event_post_id);
    $roles = get_field('roles', $event_post_id);
    $honorees_section_headline = get_field('honorees_section_headline', $event_post_id);
    $honorees_section_content = get_field('honorees_section_content', $event_post_id);
    $honorees = get_field('honorees', $event_post_id);
    $ticket_section_headline = get_field('ticket_section_headline', $event_post_id);
    $ticket_section_top_content = get_field('ticket_section_top_content', $event_post_id);
    $ticket_prices = get_field('ticket_prices', $event_post_id);
    $lodging_section_headline = get_field('lodging_section_headline', $event_post_id);
    $lodging_section_top_content = get_field('lodging_section_top_content', $event_post_id);
    $lodging = get_field('lodging', $event_post_id);
    $sponsorship_section_headline = get_field('sponsorship_section_headline', $event_post_id);
    $sponsorship_section_top_content = get_field('sponsorship_section_top_content', $event_post_id);
    $sponsorship_packages = get_field('sponsorship_packages', $event_post_id);
    $advertising_section_headline = get_field('advertising_section_headline', $event_post_id);
    $advertising_section_top_content = get_field('advertising_section_top_content', $event_post_id);
    $advertising_rates = get_field('advertising_rates', $event_post_id);

    // Initialize the array to track missing sections
    $missing_sections = [];
}
?>
<section class="sections">
    <div class="container">
        <div class="nelagala-event">
            <style>
                .nelagala-event main>section>div img {
                    mix-blend-mode: inherit !important;
                }
            </style>

            <?php
            // Assuming $event_post_id is fetched earlier as shown in previous examples.
            if ($event_post_id) :
            ?>
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
                            // Fetching the value from the event using the event post ID
                            $value = get_field($field_key, $event_post_id);
                            if ((is_array($value) && !empty($value)) || (!is_array($value) && !empty($value))) : ?>
                                <li class="menu-item"><a href="../<?= $anchor ?>"><?= ucfirst(str_replace('-', ' ', substr($anchor, 1))) ?></a></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </nav>
                <!-- !SECTION: Navigation Sidebar -->
            <?php
            endif;
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
                    <div class="header-img-main" style="background-image: url('<?php echo get_template_directory_uri(); ?>/inc/nelagala/img/header-main-img.jpg');">
                    </div>
                    <h4><? echo $display_date; ?> | More details coming soon!</h4>
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