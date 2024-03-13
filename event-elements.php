<section id="about">
    <h2>About the NELA Gala</h2>
    <time>Date and Time: <?php echo esc_html($event_datetime); ?></time>
    <p>Location: <?php echo esc_html($venue_name); ?></p>
    <?php if ($event_location) : ?>
        <div class="event-location">
            <p>Latitude: <?php echo esc_html($event_location['lat']); ?></p>
            <p>Longitude: <?php echo esc_html($event_location['lng']); ?></p>
        </div>
    <?php endif; ?>
    <?php if ($promotional_video) : ?>
        <div class="promotional-video">
            <?php echo wp_oembed_get($promotional_video); ?>
        </div>
    <?php endif; ?>
    <!-- SECTION: Display Event Roles -->
    <?php
    if (have_rows('roles')) :
        echo '<div id="roles">';
        while (have_rows('roles')) : the_row();
            $role_description = get_sub_field('role');
            $participant_id = get_sub_field('participant');

            // Fetch the participant's post title (full name)
            $participant_name = get_the_title($participant_id);

            // Fetch the summary for the participant
            $participant_summary = get_field('summary', $participant_id);

            // Construct the link to the participant's biography page
            // Assume the biography template will use the participant's name in the URL
            $participant_slug = get_post_field('post_name', $participant_id);
            $biography_link = home_url("/nelagala/" . $event_year . "/participants/" . $participant_slug);

            // Display the information
            echo '<div class="role">';
            echo '<h3>' . esc_html($role_description) . '</h3>';
            echo '<p>Participant: ' . esc_html($participant_name) . '</p>';

            if ($participant_summary) {
                echo '<p>Summary: ' . esc_html($participant_summary) . '</p>';
            }

            // Provide a link to the full biography
            echo '<a href="' . esc_url($biography_link) . '">Read full biography</a>';

            echo '</div>';
        endwhile;
        echo '</div>';
    endif;
    ?>
    <!-- !SECTION: Display Event Roles -->
</section>
<!-- !SECTION: Display Event Data (About) -->
<!-- SECTION: Display Event Honorees -->
<?php
if (have_rows('honorees')) :
    echo '<div id="honorees">';
    while (have_rows('honorees')) : the_row();
        $honor = get_sub_field('honor');
        $participant_id = get_sub_field('recipient');

        // Fetch the honoree's post title (full name)
        $participant_name = get_the_title($participant_id);

        // Fetch the summary for the honoree
        $participant_summary = get_field('summary', $participant_id);

        // Construct the link to the honoree's biography page
        // The biography template will use the honoree's name in the URL
        $participant_slug = get_post_field('post_name', $participant_id);
        $biography_link = home_url("/nelagala/" . $event_year . "/participants/" . $participant_slug);

        // Display the information
        echo '<div class="honoree">';
        echo '<h3>Honor: ' . esc_html($honor) . '</h3>';
        echo '<p>Recipient: ' . esc_html($participant_name) . '</p>';

        if ($participant_summary) {
            echo '<p>Summary: ' . esc_html($participant_summary) . '</p>';
        }

        // Provide a link to the full biography
        echo '<a href="' . esc_url($biography_link) . '">Read full biography</a>';

        echo '</div>';
    endwhile;
    echo '</div>';
else :
    echo '<div id="honorees"><p>Honorees have not been announced yet. Check back later for updates.</p></div>';
endif;
?>
<!-- !SECTION: Display Event Honorees -->
<!-- SECTION: Display Ticket Prices -->
<?php if (have_rows('ticket_prices')) : ?>
    <section id="tickets">
        <h2>Tickets</h2>
        <?php while (have_rows('ticket_prices')) : the_row();
            $ticket_type = get_sub_field('type');
            $ticket_price = get_sub_field('price');
            $ticket_tax_deduction = get_sub_field('tax_deduction');
            $ticket_description = get_sub_field('description');
        ?>
            <div class="ticket-type">
                <h3><?php echo esc_html($ticket_type); ?></h3>
                <p class="price">Price: $<?php echo esc_html($ticket_price); ?></p>
                <p class="tax-deduction">Allowable Tax Deduction: $<?php echo esc_html($ticket_tax_deduction); ?></p>
                <div class="description"><?php echo esc_html($ticket_description); ?></div>
            </div>
        <?php endwhile; ?>
    </section>
<?php else :
    $missing_sections[] = 'Tickets';
endif; ?>
<!-- !SECTION: Display Ticket Prices-->
<!-- SECTION: Display Sponsorship Packages -->
<?php if (have_rows('sponsorship_packages')) : ?>
    <section id="sponsorship_packages">
        <h2>Sponsorship Packages</h2>
        <?php while (have_rows('sponsorship_packages')) : the_row();
            $package_name = get_sub_field('package');
            $package_price = get_sub_field('price');
            $package_tax_deduction = get_sub_field('tax_deduction');
        ?>
            <div class="sponsorship-package">
                <h3><?php echo esc_html($package_name); ?></h3>
                <p class="price">Price: $<?php echo esc_html($package_price); ?></p>
                <p class="tax-deduction">Allowable Tax Deduction: $<?php echo esc_html($package_tax_deduction); ?></p>

                <!-- Amenities List -->
                <?php if (have_rows('amenities')) : ?>
                    <ul class="amenities">
                        <?php while (have_rows('amenities')) : the_row();
                            $amenity = get_sub_field('amenity');
                        ?>
                            <li><?php echo esc_html($amenity); ?></li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </section>
<?php else :
    $missing_sections[] = 'Sponsorship Packages';
endif; ?>
<!-- !SECTION: Display Sponsorship Packages-->
<!-- SECTION: Display Advertising Rates -->
<?php if (have_rows('advertising_rates')) : ?>
    <section id="advertising">
        <h2>Advertising Rates</h2>
        <?php while (have_rows('advertising_rates')) : the_row();
            $ad_type = get_sub_field('ad');
            $ad_price = get_sub_field('price');
            $ad_description = get_sub_field('description');
        ?>
            <div class="ticket-type">
                <h3><?php echo esc_html($ad_type); ?></h3>
                <p class="price">Price: $<?php echo esc_html($ad_price); ?></p>
                <div class="description"><?php echo esc_html($ad_description); ?></div>
            </div>
        <?php endwhile; ?>
    </section>
<?php else :
    $missing_sections[] = 'Advertising Rates';
endif; ?>
<!-- !SECTION: Display Ticket Prices-->

<!-- Lodging -->

<?php
if (!empty($missing_sections)) {
    // Grammar-correct string creation
    $last_item = array_pop($missing_sections); // Remove and save the last item
    $formatted_list = '';

    if (!empty($missing_sections)) {
        // If there are other items, format them with a comma and add "and" before the last item
        $formatted_list = implode(', ', $missing_sections) . ', and ' . $last_item;
    } else {
        // If there was only one item, it's now in $last_item
        $formatted_list = $last_item;
    }

    // Handle the case for exactly two items: no comma needed
    if (count($missing_sections) == 1) {
        $formatted_list = implode(' and ', $missing_sections) . ' and ' . $last_item;
    }

    echo 'Check back later for information on ' . $formatted_list . '.';
} else {
    echo 'All details are subject to change without notice. Please check back regularly for updates.';
}
?>

</article>
</main>