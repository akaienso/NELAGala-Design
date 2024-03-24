<?php
global $page_title;

// Assume $event_year is correctly fetched or set a default
$event_year = get_query_var('nelagala_year', date('Y'));

// Fetch event data for the year
$ng = fetch_nelagala_event_by_year($event_year);

get_header();
?>
<section class="sections">
    <div class="container">
        <div class="nelagala-event">
            <?php if (!empty($ng)) : ?>
                <?php
                // Show full event data including sidebar navigation and header if enabled
                $show_full_event_data = $ng['full_event_switch'];

                if ($show_full_event_data) :
                    // Pass $ng data to the sidebar-nav template part
                    nelagala_pass_template_data($ng, 'navigation');
                    get_template_part('inc/nelagala/template-parts/sidebar-nav');

                    // Pass $ng data to the event-header template part
                    nelagala_pass_template_data($ng, 'header');
                    get_template_part('inc/nelagala/template-parts/event-header');
                endif;
                ?>
                
                <!-- SECTION: Biography -->
                <main>
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
                                echo the_content();
                                if (!empty($website)) { ?>
                                    <p class="participant-website"><a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener noreferrer">Learn more about <?php the_title(); ?></a></p>
                                <?php  } ?>
                            </article>

                            <?php
            } else {
                echo "<!-- No event found for the requested year '" . esc_html($event_year) . "' -->";
            }
            ?>

        </div>
    </div>
</section>
<?php get_footer(); ?>