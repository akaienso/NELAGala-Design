<?php
$ng_data = apply_filters('nelagala_template_data_header', []);

$datetime = $ng_data['nelagala_event_datetime'];
$event_datetime = $ng_data['nelagala_event_datetime'];
$event_date = new DateTime($event_datetime);
$display_date = $event_date->format('l, F j, Y'); // Use $event_date, not $datetime
$header_venue_message = $ng_data['header_venue_message'];
$venue_name = $ng_data['nelagala_venue_name'];

$event_location = $ng_data['nelagala_event_location'];
$google_geocoding_api_key = $ng_data['google_geocoding_api_key']; 
$venue_details = fetch_event_venue_details($event_location, $google_geocoding_api_key);
?>
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
                <p class="pre-title"><?php echo get_current_event_year_with_ordinal(); ?></p>
                <h1 class="title">National Education & Leadership Awards</h1>
                <p class="post-title">Gala</p>
            </div>
        </div>
        <div class="col col-2">
            <p class="text"><?php echo  $header_venue_message; ?></p>
            <p class="col-2-title"><?php echo $venue_name; ?></p>
            <p class="text"><?php echo  $venue_details['city']; ?>, <?php echo  $venue_details['state']; ?></p>
        </div>
    </div>
    <div class="header-img-main" style="background-image: url('<?php echo get_template_directory_uri(); ?>/inc/nelagala/img/header-main-img.jpg');">
    </div>
    <h4><? echo $display_date; ?> | More details coming soon!</h4>
</header>
<!--  !SECTION: Event Header -->