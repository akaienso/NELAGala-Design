<?php
global $page_title;

$event_year = get_query_var('nelagala_year', date('Y')); // Default to current year if not specified

// Use the new function to fetch the event post ID
$event_post_id = fetch_nelagala_event_by_year($event_year);

// Define sections and their corresponding ACF field keys
$sections = [
    '#about-the-event' => [
        'content_field' => 'nelagala_event_title', 
        'headline_field' => 'about_sidebar_link_label',
    ],
    '#event-roles' => [
        'content_field' => 'roles',
        'headline_field' => 'roles_sidebar_link_label',
    ],
    '#honorees' => [
        'content_field' => 'honorees',
        'headline_field' => 'honorees_sidebar_link_label',
    ],
    '#tickets' => [
        'content_field' => 'ticket_prices',
        'headline_field' => 'tickets_sidebar_link_label',
    ],
    '#lodging' => [
        'content_field' => 'lodging',
        'headline_field' => 'lodging_sidebar_link_label',
    ],
    '#sponsorships' => [
        'content_field' => 'sponsorship_packages',
        'headline_field' => 'sponsorship_sidebar_link_label',
    ],
    '#advertising' => [
        'content_field' => 'advertising_rates',
        'headline_field' => 'advertising_sidebar_link_label',
    ]
];
if ($event_post_id) {
    // Setting the global $post object so that functions like the_title(), the_content(), etc., can work as expected.
    global $post;
    $post = get_post($event_post_id);
    setup_postdata($post);

    // Now, you correctly pass the event_post_id to get_field()
    $datetime = get_field('nelagala_event_datetime', $event_post_id);
    $show_full_event_data = get_field('full_event_switch', $event_post_id);

    // Now that we're specifying which post to get the data from, these fields will be fetched correctly.
    $event_title = get_field('nelagala_event_title', $event_post_id);
    $event_datetime = get_field('nelagala_event_datetime', $event_post_id);
    $event_date = new DateTime($event_datetime);
    $display_date = $event_date->format('l, F j, Y'); // Use $event_date, not $datetime

    $theme_image = get_field('theme_sidebar_image', $event_post_id);
    $theme_title = get_field('theme_sidebar_title', $event_post_id);
    $theme_content = wp_kses_post(get_field('theme_sidebar_content', $event_post_id));
    $show_theme_sidebar = get_field('theme_sidebar_visible', $event_post_id);

    //NOTE - This is the code to get the location of the event
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
    $roles = get_field('roles');
    $honorees_section_headline = get_field('honorees_section_headline', $event_post_id);
    $honorees_section_content = get_field('honorees_section_content', $event_post_id);
    $honorees = get_field('honorees');
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

    wp_reset_postdata();
    get_header();
?>
    <section class="sections">
        <div class="container">

            <div class="nelagala-event">
                <?php if ($show_full_event_data) : ?>
                    <!-- SECTION: Navigation Sidebar -->
                    <nav class="event-navigation nav bg_shape">
                        <div class="burger-container">
                            <button id="burger" aria-label="Open navigation menu">
                                <span class="bar topBar"></span>
                                <span class="bar btmBar"></span>
                            </button>
                        </div>
                        <ul class="menu">
                            <?php foreach ($sections as $anchor => $fields) : ?>
                                <?php
                                // Fetching both the content and the headline using the specified field keys
                                $content = get_field($fields['content_field'], $event_post_id);
                                $headline = get_field($fields['headline_field'], $event_post_id); // Fetch the headline field

                                // Check if the content field is not empty; adjust logic if necessary
                                if ((is_array($content) && !empty($content)) || (!is_array($content) && !empty($content))) : ?>
                                    <li class="menu-item"><a href="<?= esc_url($anchor); ?>"><?= esc_html($headline); ?></a></li>
                                <?php endif; ?>
                            <?php endforeach; ?>

                        </ul>
                    </nav>
                    <!-- !SECTION: Navigation Sidebar -->

                <?php endif; ?>
                <!-- SECTION: Display NELAGala Event Content -->
                <main>
                    <!--  SECTION: Event Header -->
                    <header>
                        <?php
                        if ($event_date && $event_datetime) {
                            display_event_date_countdown($event_date, $event_datetime);
                        }
                        ?>
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
                    <!--  !SECTION: Event Header -->
                    <?php if ($show_full_event_data) : ?>
                        <!--  SECTION: About the Event -->
                        <section id="about-the-event">
                            <article>
                                <h2>About the Event</h2>
                                <?php echo the_content(); ?>

                            </article>
                            <!-- NOTE: Theme Sidebar Content -->
                            <?php if ($theme_title && $theme_content && $show_theme_sidebar) : ?>
                                <aside>
                                    <?php if (!empty($theme_image)) : ?>
                                        <img src="<?php echo esc_url($theme_image['url']); ?>" alt="<?php echo esc_attr($theme_image['alt']); ?>" title="<?php echo esc_attr($theme_image['caption']); ?>" />
                                    <?php endif; ?>
                                    <h2><?php echo $theme_title; ?></h2>
                                    <?php echo $theme_content; ?>
                                </aside>
                            <?php endif; ?>
                        </section>
                        <iframe class="video" src="<?php echo  $promotional_video; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
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

                                    // Fetch the Personal Title for the participant
                                    $participant_title = get_field('title', $participant_id);

                                    // Fetch the recipient's External Website URL 
                                    $participant_website = get_field('website', $participant_id);

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
                                            <p class="personal-title"><?php echo esc_html($participant_title); ?></p>
                                            <p class="bio-summary"><?php echo esc_html($participant_summary); ?></p>
                                            <p class="read-more"><a href="<?php echo esc_url($biography_link); ?>">Read more</a></p>
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

                                    // Fetch the Personal Title for the participant
                                    $recipient_title = get_field('title', $recipient_id);

                                    // Fetch the recipient's External Website URL 
                                    $recipient_website = get_field('website', $recipient_id);

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
                                            <p class="personal-title"><?php echo esc_html($recipient_title); ?></p>
                                            <p class="bio-summary"><?php echo esc_html($recipient_summary); ?></p>
                                            <p class="read-more"><a href="<?php echo esc_url($biography_link); ?>">Read more</a></p>
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
                                        $button_label = get_sub_field('cta_button_label');
                                        $button_url = get_sub_field('cta_button_link');
                                    ?>
                                        <div class="package">
                                            <h2><?php echo esc_html($type); ?></h2>
                                            <p>Price each: $<?php echo esc_html($price); ?></p>
                                            <p>Allowable tax deduction: $<?php echo esc_html($tax_deduction); ?></p>
                                            <p><?php echo ($description); ?></p>
                                            <?php if ($button_label && $button_url) : ?>
                                                <a href="<?php echo esc_url($button_url); ?>" target="_blank"><button><?php echo esc_html($button_label); ?></button></a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </section>
                        <?php endif; ?>
                        <!--  !SECTION: Tickets -->
                        <!-- SECTION: location -->
                        <div id="lodging" class="map-container">
                            <div id="map" class="map"></div>
                        </div>
                        <section id="location" class="hotels-cards">
                            <h2>Venue and Accommodations</h2>
                            <?php if (have_rows('lodging')) : ?>
                                <div class="packages">
                                    <?php while (have_rows('lodging')) : the_row();
                                        $name = get_sub_field('property');
                                        $location = get_sub_field('location');
                                        $address = $location['street_number'] . ' ' . $location['street_name'] . '<br>' . $location['city'] . ', ' . $location['state'] . ' ' . $location['post_code'];
                                        $phone = get_sub_field('telephone');
                                        $booking_url = get_sub_field('website');
                                    ?>
                                        <div class="package">
                                            <h2><?php echo esc_html($name); ?></h2>
                                            <p><?php echo $address; ?></p>
                                            <p><?php echo $phone; ?></p>
                                            <?php if ($booking_url) : ?>
                                                <a href="<?php echo esc_url($booking_url); ?>" target="_blank"><button>Book Now</button></a>
                                            <?php endif; ?>
                                        </div>

                                    <?php endwhile; ?>

                                </div>


                            <?php endif; ?>
                        </section>
                        <!-- !SECTION: Location -->
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
                                        $button_label = get_sub_field('cta_button_label');
                                        $button_url = get_sub_field('Call-to-Action-URL');
                                    ?>
                                        <div class="package">
                                            <h2><?php echo esc_html($package); ?></h2>
                                            <p><span>Price each:</span> $<?php echo esc_html($price); ?></p>
                                            <p><span>Allowable tax deduction:</span> $<?php echo esc_html($tax_deduction); ?></p>
                                            <p><?php echo ($amenities); ?></p>
                                            <?php if (have_rows('amenities')) : ?>
                                                <ul>
                                                    <?php while (have_rows('amenities')) : the_row(); ?>
                                                        <li><?php the_sub_field('amenity'); ?></li>
                                                    <?php endwhile; ?>
                                                </ul>
                                            <?php endif;

                                            if ($button_label && $button_url) : ?>
                                                <a href="<?php echo esc_url($button_url); ?>" target="_blank"><button><?php echo esc_html($button_label); ?></button></a>
                                            <?php endif; ?>

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
                                        $button_label = get_sub_field('cta_button_label');
                                        $button_url = get_sub_field('cta_link');
                                    ?>
                                        <div class="package">
                                            <h2><?php echo esc_html($ad); ?></h2>
                                            <p>Price each: $<?php echo esc_html($price); ?></p>
                                            <p><?php echo ($description); ?></p>


                                            <?php if ($button_label && $button_url) : ?>
                                                <a href="<?php echo esc_url($button_url); ?>" target="_blank"><button><?php echo esc_html($button_label); ?></button></a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </section>
                        <?php endif; ?>
                        <!--  !SECTION: Advertising Rates . -->
                    <?php endif; ?>
                </main>
                <!-- !SECTION: Display NELAGala Event Content -->
            <?php
        } else {
            echo "<!-- No event found for the requested year '" . esc_html($event_year) . "' -->";
        }
            ?>

            </div>
        </div>
    </section>
    <?php get_footer(); ?>