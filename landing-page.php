<?php
global $page_title;
$event_year = get_query_var('nelagala_year', date('Y')); // Default to current year if not specified
$ng = fetch_nelagala_event_by_year($event_year);
 $full_event_switch = $ng['full_event_switch'] ?: false;
$is_demo_mode = !$full_event_switch;
get_header();
?>
<section class="sections">
    <div class="container">
        <!-- SECTION: NELAGala Event Page -->
        <div id="nelagala" class="nelagala-event <?=$is_demo_mode ? 'demo_mode' : '' ?>">
            <?php if (!empty($ng)) {

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
                // ACF field values
                $event_title = esc_html($ng['nelagala_event_title']);
                $datetime = $ng['nelagala_event_datetime'];
                $datetime_detail = $ng['datetime_detail'];
                $event_datetime = $ng['nelagala_event_datetime'];
                $event_date = new DateTime($event_datetime);
                $display_date = $event_date->format('l, F j, Y'); // Use $event_date, not $datetime

                $about_section_headline = $ng['about_section_headline'];
                $theme_image = $ng['theme_sidebar_image'];
                $theme_title = esc_html($ng['theme_sidebar_title']);
                $theme_content = wp_kses_post($ng['theme_sidebar_content']);
                $show_theme_sidebar = $ng['show_theme_sidebar'];
                $theme_sidebar_demo = $ng['theme_sidebar_demo'];

                //NOTE - This is the code to get the location of the event
                // This returns an array for Google Map field
                $event_location = $ng['nelagala_event_location'];
                $lat = $event_location['lat'];
                $lng = $event_location['lng'];
                $google_api_key = $ng['google_api_key'];
                $google_geocoding_api_key = $ng['google_geocoding_api_key'];
                $response = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&key=$google_geocoding_api_key");

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
                $header_venue_message = $ng['header_venue_message'];
                $venue_name = $ng['nelagala_venue_name'];
                $promotional_video = esc_url($ng['promotional_video']);
                $roles_section_headline = $ng['roles_section_headline'];
                $roles_section_content = $ng['roles_section_content'];
                $roles = $ng['roles'];
                $honorees_sections_headline = $ng['honorees_sections_headline'];
                $honorees_section_content = $ng['honorees_section_content'];
                $honorees = $ng['honorees'];
                $ticket_section_headline = $ng['ticket_section_headline'];
                $ticket_section_top_content = $ng['ticket_section_top_content'];
                $ticket_prices = $ng['ticket_prices'];
                $lodging_section_headline = $ng['lodging_section_headline'];
                $lodging_section_top_content = $ng['lodging_section_top_content'];
                $lodging = $ng['lodging'];
                $sponsorship_section_headline = $ng['sponsorship_section_headline'];
                $sponsorship_section_top_content = $ng['sponsorship_section_top_content'];
                $sponsorship_packages = $ng['sponsorship_packages'];
                $advertising_section_headline = $ng['advertising_section_headline'];
                $advertising_section_top_content = $ng['advertising_section_top_content'];
                $advertising_rates = $ng['advertising_rates'];

                // NOTE: Display Sidebar Navigation
                // Fetch the NELAGala event data for $event_year
                $ng_data = fetch_nelagala_event_by_year($event_year);
                nelagala_pass_template_data($ng, 'navigation');
                $args = array(
                    'event_year' => $event_year,
                    'event_year' => $event_year,
                );
                get_template_part('inc/nelagala/template-parts/section-navigation', null, $args);

            ?>
                <!-- SECTION: Display NELAGala Event Content -->
                <main>
                    <?php
                    // NOTE: Display the event header
                    nelagala_pass_template_data($ng_data, 'header');
                    get_template_part('inc/nelagala/template-parts/section-header');

                    if ($display_about) : ?>
                        <!--  SECTION: About the Event -->
                        <section id="about-the-event">
                            <article class="<?= $display_theme_sidebar ? '' : 'full-width'; ?>">
                                <h2><?php echo $about_section_headline; ?></h2>
                                <?php echo the_content(); ?>
                            </article>
                            <?php if ($display_theme_sidebar) : ?>

                                <!-- NOTE: Theme Sidebar Content -->
                                <?php if ($theme_title && $theme_content) : ?>
                                    <aside>
                                        <?php if (!empty($theme_image)) : ?>
                                            <img src="<?php echo esc_url($theme_image['url']); ?>" alt="<?php echo esc_attr($theme_image['alt']); ?>" title="<?php echo esc_attr($theme_image['caption']); ?>" />
                                        <?php endif; ?>
                                        <h2><?php echo $theme_title; ?></h2>
                                        <?php echo $theme_content; ?>
                                    </aside>
                            <?php endif;
                            endif; ?>

                        </section>
                        <?php if ($display_promotional_video) : ?>
                            <iframe class="video" src="<?php echo  $promotional_video; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        <?php endif; ?>
                        <!--  !SECTION: About the Event -->
                    <?php endif;
                    if ($display_roles) : ?>
                        <!--  SECTION: Event Roles -->
                        <?php
                        if (have_rows('roles')) :
                        ?>
                            <section id="event-roles" class="participants">
                                <h2><?php echo $roles_section_headline ?></h2>
                                <?php if ($roles_section_content) : ?>
                                    <span class="sub-text"><?php echo $roles_section_content; ?></span>
                                <?php endif;
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
                                            // Check if there's a biography link
                                            if (!empty($biography_link)) {

                                                // If a link exists, wrap the image with an <a> tag
                                                echo '<a class="biography-link" href="' . esc_url($biography_link) . '"><img src="' . esc_url($url) . '" alt="' . esc_attr($alt) . '"></a>';
                                            } else {
                                                // If no link exists, display just the image
                                                echo '<img src="' . esc_url($url) . '" alt="' . esc_attr($alt) . '">';
                                            }
                                        }
                                        ?>
                                        <div>
                                            <h3><?php echo esc_html($role_description); ?></h3>
                                            <p class="full-name"><a href="<?php echo esc_url($biography_link); ?>"><?php echo esc_html($participant_name); ?></a></p>
                                            <p class="personal-title"><?php echo esc_html($participant_title); ?></p>
                                            <p class="bio-summary"><?php echo esc_html($participant_summary); ?></p>
                                            <p class="read-more"><a href="<?php echo esc_url($biography_link); ?>">Read more</a></p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </section>
                        <?php endif; ?>
                        <!--  !SECTION: Event Roles -->
                    <?php endif;
                    if ($display_honorees) : ?>
                        <!--  SECTION: Event Honorees -->
                        <?php
                        if (have_rows('honorees')) :
                        ?>
                            <section id="honorees" class="participants">
                                <h2><?php echo esc_html($honorees_sections_headline) ?></h2>
                                <?php if ($honorees_section_content) : ?>
                                    <span class="sub-text"><?php echo $honorees_section_content; ?></span>
                                <?php endif;
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
                                            <a class="biography-link" href="<?php echo esc_url($biography_link); ?>"><img src="<?php echo esc_url($url); ?>" alt="Photograph of <?php echo esc_attr($alt); ?>"></a><?php
                                                                                                                                                                                                                } ?>
                                        <div>
                                            <h3><?php echo esc_html($honor_description); ?></h3>
                                            <p class="full-name"><a href="<?php echo esc_url($biography_link); ?>"><?php echo esc_html($recipient_name); ?></a></p>
                                            <p class="personal-title"><?php echo esc_html($recipient_title); ?></p>
                                            <p class="bio-summary"><?php echo esc_html($recipient_summary); ?></p>
                                            <p class="read-more"><a href="<?php echo esc_url($biography_link); ?>">Read more</a></p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </section>
                        <?php endif; ?>
                        <!--  !SECTION: Event Honorees -->
                    <?php endif;
                    if ($display_tickets) : ?>
                        <!--  SECTION: Tickets -->
                        <?php
                        if (have_rows('ticket_prices')) :
                        ?>
                            <section id="tickets" class="tickets">
                                <h2><?php echo esc_html($ticket_section_headline) ?></h2>
                                <?php if ($ticket_section_top_content) : ?>
                                    <span class="sub-text"><?php echo $ticket_section_top_content; ?></span>
                                <?php endif; ?>
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
                    <?php endif; ?>
                    <?php if ($display_map) : ?>
                        <!-- SECTION: map -->
                        <div id="lodging" class="map-container">
                            <div id="map" class="map"></div>
                        </div>
                        <!-- !SECTION: map -->
                    <?php endif;
                    if ($display_lodging) :  ?>
                        <!-- SECTION: lodging -->
                        <section id="location" class="hotels-cards">
                            <h2><?php echo esc_html($lodging_section_headline) ?></h2>
                            <?php if ($lodging_section_top_content) : ?>
                                <span class="sub-text"><?php echo $lodging_section_top_content; ?></span>
                            <?php endif;
                            if (have_rows('lodging')) : ?>
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
                        <!-- !SECTION: lodging -->
                    <?php endif;
                    if ($display_sponsorships) : ?>
                        <!--  SECTION: sponsorships -->
                        <?php
                        if (have_rows('sponsorship_packages')) :
                        ?>
                            <section id="sponsorships" class="sponsorships">
                                <h2><?php echo esc_html($sponsorship_section_headline) ?></h2>
                                <?php if ($sponsorship_section_top_content) : ?>
                                    <span class="sub-text"><?php echo $sponsorship_section_top_content; ?></span>
                                <?php endif; ?> <div class="packages">
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
                        <!--  !SECTION: sponsorships -->
                    <?php endif;
                    if ($display_advertising) : ?>
                        <!--  SECTION: advertising -->
                        <?php
                        if (have_rows('advertising_rates')) :
                        ?>
                            <section id="advertising" class="advertising">
                                <h2><?php echo esc_html($advertising_section_headline) ?></h2>
                                <?php if ($advertising_section_top_content) : ?>
                                    <span class="sub-text"><?php echo $advertising_section_top_content; ?></span>
                                <?php endif; ?>
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
                        <!--  !SECTION: advertising -->
                    <?php endif; ?>
                </main>
                <!-- !SECTION: Display NELAGala Event Content -->
            <?php
            } else {
                echo "<!-- No event found for the requested year '" . esc_html($event_year) . "' -->";
            }
            ?>
        </div>
        <!-- !SECTION: NELAGala Event Page -->
    </div>
</section>
<?php get_footer(); ?>