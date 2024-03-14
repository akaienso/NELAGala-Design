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
    '#lodging' => 'lodging',
    '#sponsorships' => 'sponsorship_packages',
    '#advertising' => 'advertising_rates',
];

if ($events->have_posts()) : while ($events->have_posts()) : $events->the_post();
        $datetime = get_field('nelagala_event_datetime');
?>

        <section class="sections">
            <div class="container">
                <div class="nelagala-event">
                    <!-- SECTION: Navigation Sidebar -->
                    <nav class="event-navigation nav">
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
                                    <li class="menu-item"><a href="<?= $anchor ?>"><?= ucfirst(str_replace('-', ' ', substr($anchor, 1))) ?></a></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                    <!-- !SECTION: Navigation Sidebar -->

                    <!-- SECTION: Display Event Data  -->
                    <?php
                    if ($datetime && date('Y', strtotime($datetime)) == $event_year) {
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
                        // URL from oEmbed field
                        $promotional_video = get_field('promotional_video');
                        // Repeater fields
                        $roles = get_field('roles');
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
                                <img class="header-img" src="<?php echo get_template_directory_uri() . '/inc/nelagala/img/sicilia.png'; ?>" alt="the Trinacria">
                                <h2>Save the Date</h2>
                                <div class="row">
                                    <div class="col divider">
                                        <h3>Sons of Italy Foundation presents</h3>
                                        <div class="title-wrap">
                                            <p class="pre-title"><?php echo get_current_event_year_with_ordinal(); ?>
                                            </p>
                                            <h1 class="title">"<?php echo  $event_title; ?></h1>
                                            <p class="pre-title"><span class="yr">Gala</span></p>
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
                            <!--  !SECTION: Event Header -->
                            <!--  SECTION: About the Event -->
                            <section id="about-the-event">
                                <h2>About the Event</h2>
                                <?php echo the_content(); ?>
                                <iframe class="video" src="<?php echo  $promotional_video; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                            </section>
                            <!--  !SECTION: About the Event -->
                            <!--  SECTION: Event Roles -->
                            <?php
                            if (have_rows('roles')) :
                            ?>
                                <section id="event-roles" class="participants">
                                    <h2>Who's who...</h2>

                                    <?php
                                    while (have_rows('roles')) : the_row();
                                        $role_description = get_sub_field('role');
                                        $participant_id = get_sub_field('participant');


                                        // Fetch the participant's post title (full name)
                                        $participant_name = get_the_title($participant_id);

                                        // Fetch the participant's photo 
                                        $participant_photo = get_field('photo', $participant_id);

                                        // Fetch the summary for the participant
                                        $participant_summary = get_field('summary', $participant_id);

                                        // Construct the link to the participant's biography page
                                        // Assume the biography template will use the participant's name in the URL
                                        $participant_slug = get_post_field('post_name', $participant_id);
                                        $biography_link = home_url("/nelagala/" . $participant_slug);
                                    ?>
                                        <div class="row-container">
                                            <?php

                                            if (!empty($participant_photo)) {
                                                // Image variables
                                                $url = $participant_photo['url'];
                                                $alt = $participant_photo['alt'];

                                            ?>
                                                <img src="<?php echo esc_url($url); ?>" alt="Photograph of <?php echo esc_attr($alt); ?>"><?php
                                                                                                                                        } ?>
                                            <div>
                                                <h3><?php echo esc_html($role_description); ?></h3>
                                                <p class="full-name"><?php echo esc_html($participant_name); ?></p>
                                                <p class="bio-summary"><?php echo esc_html($participant_summary); ?></p>
                                                <p><a href="<?php echo esc_url($biography_link); ?>">Read more</a></p>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </section>
                            <?php endif; ?>
                            <!--  !SECTION: Event Roles -->
                            <!--  SECTION: Event Honorees -->
                            <?php
                            if (have_rows('honorees')) :
                            ?>

                                <section id="honorees" class="participants">
                                    <h2>Honorees</h2>

                                    <?php
                                    while (have_rows('honorees')) : the_row();
                                        $honor_description = get_sub_field('honor');
                                        $recipient_id = get_sub_field('recipient');


                                        // Fetch the recipient's post title (full name)
                                        $recipient_name = get_the_title($recipient_id);

                                        // Fetch the recipient's photo 
                                        $recipient_photo = get_field('photo', $recipient_id);

                                        // Fetch the summary for the participant
                                        $recipient_summary = get_field('summary', $recipient_id);

                                        // Construct the link to the participant's biography page
                                        // Assume the biography template will use the participant's name in the URL
                                        $recipient_slug = get_post_field('post_name', $recipient_id);
                                        $biography_link = home_url("/nelagala/" . $recipient_slug);
                                    ?>
                                        <div class="row-container reverse">
                                            <?php

                                            if (!empty($recipient_photo)) {
                                                // Image variables
                                                $url = $recipient_photo['url'];
                                                $alt = $recipient_photo['alt'];

                                            ?>
                                                <img src="<?php echo esc_url($url); ?>" alt="Photograph of <?php echo esc_attr($alt); ?>"><?php
                                                                                                                                        } ?>
                                            <div>
                                                <h3><?php echo esc_html($honor_description); ?></h3>
                                                <p class="full-name"><?php echo esc_html($recipient_name); ?></p>
                                                <p class="bio-summary"><?php echo esc_html($recipient_summary); ?></p>
                                                <p><a href="<?php echo esc_url($biography_link); ?>">Read more</a></p>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </section>
                            <?php endif; ?>
                            <!--  !SECTION: Event Roles -->
                            <!--  SECTION: Tickets -->
                            <?php
                            if (have_rows('ticket_prices')) :
                            ?>
                                <section id="tickets" class="tickets">
                                    <h2><?php echo $ticket_section_headline ?></h2>
                                    <span class="sub-text"><?php echo $ticket_section_top_content; ?></span>
                                    <div class="packages">
                                        <?php
                                        while (have_rows('ticket_prices')) : the_row();
                                            $type = get_sub_field('type');
                                            $price = get_sub_field('price');
                                            $tax_deduction = get_sub_field('tax_deduction');
                                            $description = get_sub_field('description');
                                        ?>
                                            <div class="package">
                                                <h2><?php echo esc_html($type); ?></h2>
                                                <p>Price each: $<?php echo esc_html($price); ?></p>
                                                <p>Allowable tax deduction: $<?php echo esc_html($tax_deduction); ?></p>
                                                <p><?php echo ($description); ?></p>
                                                <button>Buy Now</button>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </section>
                            <?php endif; ?>
                            <!--  !SECTION: Tickets -->

                            <!--  SECTION: sponsorship packagess -->
                            <?php
                            if (have_rows('sponsorship_packages')) :
                            ?>
                                <section id="sponsorships" class="sponsorships">
                                    <h2><?php echo $sponsorship_section_headline ?></h2>
                                    <span class="sub-text"><?php echo $sponsorship_section_top_content; ?></span>
                                    <div class="packages">
                                        <?php
                                        while (have_rows('sponsorship_packages')) : the_row();
                                            $package = get_sub_field('package');
                                            $price = get_sub_field('price');
                                            $tax_deduction = get_sub_field('tax_deduction');
                                            $description = get_sub_field('description');
                                        ?>
                                            <div class="package">
                                                <h2><?php echo esc_html($package); ?></h2>
                                                <p><em></em>Price each: $<?php echo esc_html($price); ?></p>
                                                <p>Allowable tax deduction: $<?php echo esc_html($tax_deduction); ?></p>
                                                <p><?php echo ($amenities); ?></p>
                                                <?php if (have_rows('amenities')) : ?>
                                                    <ul>
                                                        <?php while (have_rows('amenities')) : the_row(); ?>
                                                            <li><?php the_sub_field('amenity'); ?></li>
                                                        <?php endwhile; ?>
                                                    </ul>
                                                <?php endif; ?>
                                                <button>Buy Now</button>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </section>
                            <?php endif; ?>
                            <!--  !SECTION: Sponsorship Packages. -->
                            <!--  SECTION: Advertising Rates -->
                            <?php
                            if (have_rows('advertising_rates')) :
                            ?>
                                <section id="advertising" class="advertising">
                                    <h2><?php echo $advertising_section_headline ?></h2>
                                    <span class="sub-text"><?php echo $advertising_section_top_content; ?></span>
                                    <div class="packages">
                                        <?php
                                        while (have_rows('advertising_rates')) : the_row();
                                            $ad = get_sub_field('ad');
                                            $price = get_sub_field('price');
                                            $description = get_sub_field('description');
                                        ?>
                                            <div class="package">
                                                <h2><?php echo esc_html($ad); ?></h2>
                                                <p>Price each: $<?php echo esc_html($price); ?></p>
                                                <p><?php echo ($description); ?></p>
                                                <button>Buy Now</button>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </section>
                            <?php endif; ?>
                            <!--  !SECTION: Advertising Rates . -->
                        </main>
            <?php } else {
                        echo '<p>The event year does not match the date specified in the event details.</p>';
                    }
                endwhile;
            else :
                echo '<p>No event found for this year.</p>';
            endif;
            wp_reset_postdata();


            ?>
                </div>
            </div>
        </section>
        <?php get_footer(); ?>