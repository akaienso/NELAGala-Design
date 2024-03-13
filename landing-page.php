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

// Loop through the event(s)
if ($events->have_posts()) : while ($events->have_posts()) : $events->the_post();
        // Validate year from post title against 'nelagala_event_datetime'
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
                            <li class="menu-item"><a href="#about-the-event">About the Event</a></li>
                            <li class="menu-item"><a href="#event-roles">Who's Who</a></li>
                            <li class="menu-item"><a href="#honorees">Honorees</a></li>
                            <li class="menu-item"><a href="#tickets">Tickets</a></li>
                            <li class="menu-item"><a href="#lodging">Lodging</a></li>
                            <li class="menu-item"><a href="#sponsorships">Sponsorship Packages</a></li>
                            <li class="menu-item"><a href="#advertising">Advertising Rates</a></li>
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
                        $venue_name = get_field('nelagala_venue_name');
                        // URL from oEmbed field
                        $promotional_video = get_field('promotional_video');
                        // Repeater fields
                        $roles = get_field('roles');
                        $honorees = get_field('honorees');
                        $ticket_prices = get_field('ticket_prices');
                        $sponsorship_packages = get_field('sponsorship_packages');
                        $advertising_rates = get_field('advertising_rates');
                        $lodging = get_field('lodging');

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
                                            <p class="pre-title">The <span class="yr">35</span> th Annual </p>
                                            <h1 class="title">"<?php echo  $event_title; ?></h1>
                                            <p class="pre-title"><span class="yr">Gala</span></p>
                                        </div>
                                    </div>
                                    <div class="col col-2">
                                        <p class="text">Celebrate the Region of Sicily with us</p>
                                        <p class="col-2-title">Ronald Reagan Building & International Trade Center</p>
                                        <p class="text">Washington, D.C.</p>
                                    </div>
                                </div>

                                <div class="header-img-main">
                                    <img src="<?php echo get_template_directory_uri() . '/inc/nelagala/img/header-main-img.jpg'; ?>" alt="Teatro Antico di Taormina">
                                </div>
                                <div class="header-footer-text">Thursday, May 23 2024 | More details coming soon!</div>
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
                                    <h2>Ticket Prices</h2>
                                    <p class="sub-text">Tickets available on sale now Saepe eaque nisi fuga cumque amet. Et dolores iusto quibusdam molestiae laudantium facere vel sequi tenetur. Dignissimos adipisci consequuntur officiis commodi repellat omnis. </p>
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
                                    <h2>Sponsorship Packages</h2>
                                    <p class="sub-text">Tickets available on sale now Saepe eaque nisi fuga cumque amet. Et dolores iusto quibusdam molestiae laudantium facere vel sequi tenetur. Dignissimos adipisci consequuntur officiis commodi repellat omnis. </p>
                                    <div class="packages">
                                        <?php
                                        while (have_rows('sponsorship_packages')) : the_row();
                                            $type = get_sub_field('package');
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
                                                    <ul >
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
                                    <h2>Advertising Rates</h2>
                                    <p class="sub-text">Tickets available on sale now Saepe eaque nisi fuga cumque amet. Et dolores iusto quibusdam molestiae laudantium facere vel sequi tenetur. Dignissimos adipisci consequuntur officiis commodi repellat omnis. </p>
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