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

$participant_name_url = get_query_var('nelagala_participant_name'); // Get the participant name from the URL
$participant_name = str_replace('-', ' ', $participant_name_url); // Replace hyphens with spaces to match the actual name

$current_year = date('Y');
$upcomingEventYear = find_upcoming_event_year(); // Assume this function exists and returns the year of the upcoming event
$matched_events = [];

$args = [
    'post_type' => 'nelagala_event',
    'posts_per_page' => -1, // Retrieve all events
    'orderby' => 'meta_value',
    'meta_key' => 'nelagala_event_datetime',
    'order' => 'ASC'
];

$query = new WP_Query($args);

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        $event_year = get_field('nelagala_event_datetime');
        $event_year = date('Y', strtotime($event_year));

        // Assume 'nelagala_roles' is the repeater field for roles
        if (have_rows('nelagala_roles')) {
            while (have_rows('nelagala_roles')) {
                the_row();
                // Assume sub fields are 'role_name' and 'participant_name'
                $role_name = get_sub_field('role_name');
                $participant_name_field = get_sub_field('participant_name');

                if (strcasecmp($participant_name_field, $participant_name) == 0) {
                    $matched_events[$event_year][] = [
                        'role' => $role_name,
                        'event_title' => get_the_title(),
                        'event_link' => get_the_permalink()
                    ];
                }
            }
        }

       // Similar block for 'nelagala_honorees' repeater field
        if (have_rows('nelagala_honorees')) {
            while (have_rows('nelagala_honorees')) {
                the_row();
                // Adjust sub fields as necessary for your honorees' structure
                $honor_title = get_sub_field('honor_title'); // For example
                $participant_name_field = get_sub_field('participant_name');

                if (strcasecmp($participant_name_field, $participant_name) == 0) {
                    $matched_events[$event_year][] = [
                        'role' => $honor_title, // Adjust key name if you prefer a different label for honorees
                        'event_title' => get_the_title(),
                        'event_link' => get_the_permalink()
                    ];
                }
            }
        }

    }
}

wp_reset_postdata();

ksort($matched_events); // Sort the matched events by year
?>


<main>
    <header>
        <img class="header-img" src="./img/header-img.png" alt="">
        <div class="row">
            <div class="col divider">
                <h3>Sons of Italy Foundation presents</h3>

                <div class="title-wrap">
                    <p class="pre-title">The <span class="yr">35</span> th Annual </p>
                    <h1 class="title">National Academic & Leadership Awards</h1>
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
            <img src="./img/header-main-img.jpg" alt="">
        </div>

        <div class="header-footer-text">Thursday, May 23 2024 | More details coming soon!</div>
    </header>
    <section id="Biography" class="participants">
        <h2><?php echo $honor_title;?></h2>
        <div>
            <img src="img\faces\zappia.jpg">
            <div>
                <p class="full-name"><?php echo $participant_name;?></p>
                <p class="personal-title"> <?php $participant_name_url;?></p>

            </div>
        </div>
        <article>
           <?php the_content();?>
        </article>
        <section class="past-honors">
            <ul>
                <li><em>2003 — 2024:</em> Master of Ceremonies</li>
                <li><em>2019:</em> SIF Lifetime Achievement Award in the Arts</li>
            </ul>
        </section>
        <a href="index.html">Home</a>
    </section>
</main>

            </div>
        </div>
    </section>
<?php 
//get_footer();
 ?>