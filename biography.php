<?php
global $page_title;
global $is_demo_mode;
global $is_preview_mode;

$event_year = get_query_var('nelagala_year', date('Y'));
$ng = fetch_nelagala_event_by_year($event_year);

get_header();
?>
<section class="sections">
    <div class="container">
        <div class="nelagala-event">
            <style>
                .nelagala-event main>section>div img {
                    mix-blend-mode: inherit;
                }
            </style>
            <?php if (!empty($ng)) {

                // NOTE: Display Sidebar Navigation
                // Fetch the NELAGala event data for $event_year
                $ng_data = fetch_nelagala_event_by_year($event_year);
                nelagala_pass_template_data($ng, 'navigation');
                $args = array(
                    'path' => '/nelagala/',
                    'event_year' => $event_year,
                    'preview_mode' => $is_preview_mode,
                );
                get_template_part('inc/nelagala/template-parts/section-navigation', null, $args);
            ?>

                <!-- SECTION: Biography -->
                <main>
                <?php
                    // NOTE: Display the event header
                    nelagala_pass_template_data($ng_data, 'header');
                    get_template_part('inc/nelagala/template-parts/section-header');
                    ?>
                    <!-- SECTION: Data dump of nelagala-participant field group -->
                    <section id="Biography" class="participants">
                        <?php
                        $participant_slug = get_query_var('nelagala_participant_name');
                        $participant_posts = new WP_Query(array(
                            'name' => $participant_slug,
                            'post_type' => 'nelagala-participant',
                            'posts_per_page' => 1,
                        ));

                        if ($participant_posts->have_posts()) : while ($participant_posts->have_posts()) :            $participant_posts->the_post(); ?>

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
                                        //echo print_r($photo, true);
                                        $url = $photo['url'];
                                        $alt = $photo['alt']; 
                                        $photo_title = $photo['description']; ?>
                                        <img src="<?php echo esc_url($url); ?>" title="<?php echo esc_attr($photo_title); ?>" alt="<?php echo esc_attr($alt); ?>">
                                    <?php } ?>

                                    <div>
                                        <h3 class="full-name"><?php the_title(); ?></h3>
                                        <?php if (!empty($title)) { ?>
                                            <p class="personal-title"><?php echo esc_html($title); ?></p>
                                        <?php  } ?>
                                    </div>

                                </div>

                                <article>
                                    
                                    <?php
                                    // Display the full biography
                                    echo the_content();

                                    // Display the optional external website link
                                    if (!empty($website)) { ?>
                                        <p class="participant-website">Click here to <a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener noreferrer">Learn more about <?php the_title(); ?></a></p>
                                    <?php  } ?>
                                </article>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </section>
                    <!-- !SECTION: Data dump of nelagala-participant field group -->
                </main>
                <!-- !SECTION: Biography -->
            <?php
            } else {
                echo "<!-- No event found for the requested year '" . esc_html($event_year) . "' -->";
            }
            ?>

        </div>
    </div>
</section>
<?php get_footer(); ?>