<?php
$ng = apply_filters('nelagala_template_data_navigation', []);
$path = $args['path'] ?? '';
$event_year = $args['event_year'] ?? '';

// $show_sponsor_list = $ng['show_sponsor_list'];
$sponsor_list_headline = $ng['sponsor_list_headline'];
$sponsor_list_header_text = $ng['sponsor_list_header_text'];
$sponsor_list_footer_text = $ng['sponsor_list_footer_text'];

$nelagala_sponsor = $ng['nelagala_sponsor'];


// if ($show_sponsor_list) :
?>
    <!--  SECTION: Sponsor List -->
 
    <section id="sponsor-list" class="list">
    <?php

    $sponsors_by_level = [];

    if ($nelagala_sponsor) {
        foreach ($nelagala_sponsor as $sponsor) {
            $level = $sponsor['sponsor_level'];
            if (!isset($sponsors_by_level[$level])) {
                $sponsors_by_level[$level] = [];
            }
            $sponsors_by_level[$level][] = $sponsor;
        }
    }

    $level_order = ['gold', 'silver', 'bronze'];

    function display_sponsors_by_level($sponsors_by_level, $level_order) {
        foreach ($level_order as $level) {
            if (isset($sponsors_by_level[$level])) {
                echo '<section class="sponsorship-level" data-level="' . esc_attr($level) . '">';
                echo '<h1>' . ucfirst($level) . ' Sponsors</h1>';
                echo '<ul class="sponsor-list">';
                
                foreach ($sponsors_by_level[$level] as $sponsor) {
                    $name = esc_html($sponsor['name']);
                    $logo = esc_url($sponsor['logo']);
                    $link = esc_url($sponsor['external_link']);

                    echo '<li class="sponsor-item">';
                    echo '<figure>';
                    echo '<a href="' . $link . '">';
                    echo '<img src="' . $logo . '" alt="' . $name . ' Logo">';
                    echo '<figcaption>' . $name . '</figcaption>';
                    echo '</a>';
                    echo '</figure>';
                    echo '</li>';
                }

                echo '</ul>';
                echo '</section>';
            }
        }
    }

    display_sponsors_by_level($sponsors_by_level, $level_order);

?>
    </section>
    <!--  !SECTION: Sponsor List -->
