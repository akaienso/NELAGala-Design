<?php
global $is_demo_mode;
$ng_data = apply_filters('nelagala_template_data_header', []);

// In section-header.php, ensure all variables used are set and default to visible values if not
// This is more of a brute-force check and may not be necessary if the root cause is found
$venue_details['city'] = $venue_details['city'] ?? 'Default City';
$venue_details['state'] = $venue_details['state'] ?? 'Default State';
$header_venue_message = $header_venue_message ?? 'Default Venue Message';
$theme_sidebar_title = $theme_sidebar_title ?? 'Default Theme Sidebar Title';
$datetime = $ng_data['nelagala_event_datetime'];
$datetime_detail = $ng_data['datetime_detail'];
$event_datetime = $ng_data['nelagala_event_datetime'];
$event_date = new DateTime($event_datetime);
$display_date = $event_date->format('l, F j, Y'); // Use $event_date, not $datetime
$header_venue_message = $ng_data['header_venue_message'];
$theme_sidebar_title = $ng_data['theme_sidebar_title'];
$venue_name = $ng_data['nelagala_venue_name'];
$event_calendar_file = $ng_data['event_calendar_file'];
$event_location = $ng_data['nelagala_event_location'];
$google_geocoding_api_key = $ng_data['google_geocoding_api_key'];
$venue_details = fetch_event_venue_details($event_location, $google_geocoding_api_key);
?>
<!--  SECTION: Event Header -->
<header>
    <?php if ($is_demo_mode) : ?>
        <h2>Save the Date</h2>
        <div class="row save-the-date">
        <?php else : ?>
            <div class="row">
            <?php endif; ?>
            <img class="header-img" src="<?php echo get_template_directory_uri() . '/inc/nelagala/img/sicilia.png'; ?>" alt="the Trinacria">
            <div class="col divider">
                <h3>Sons of Italy Foundation<sup>&reg;</sup> presents</h3>
                <div class="title-wrap">
                    <p class="pre-title"><?php echo get_current_event_year_with_ordinal(); ?></p>
                    <h1 class="title">National Education & Leadership Awards</h1>
                    <p class="post-title">Gala</p>
                </div>
            </div>
            <div class="col col-2">
                <p class="text"><?= !empty($header_venue_message) ? $header_venue_message : $theme_sidebar_title ?></p>
                <p class="col-2-title"><?php echo $venue_name; ?></p>
                <p class="text"><?php echo  $venue_details['city']; ?>, <?php echo  $venue_details['state']; ?></p>
            </div>
            </div>
            <div class="header-img-main" style="background-image: url('<?php echo get_template_directory_uri(); ?>/inc/nelagala/img/header-main-img.jpg');">
            </div>
            <h4>
                <?php if (!empty($event_calendar_file)) : ?>
                    <a href="<?= $event_calendar_file; ?>" title="Click to download an ICS file for your calendar">
                    <?php endif; ?>
                    <span><?php echo $display_date; ?></span>
                    <?php if ($is_demo_mode || empty($datetime_detail)) : ?>
                        <span>More details coming soon!</span>
                    <?php else : ?>
                        <span><?php echo $datetime_detail; ?></span>
                    <?php endif;
                    if (!empty($event_calendar_file)) : ?>
                    </a>
                <?php endif; ?>
            </h4>
</header>
<!--  !SECTION: Event Header -->