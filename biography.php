<?php 
function custom_theme_styles() {
    wp_enqueue_style('local-style', get_stylesheet_directory_uri() . '/style.css');
    wp_enqueue_style('typekit-fonts', 'https://use.typekit.net/zcb5mzu.css');
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital@1&display=swap', false);
    wp_enqueue_style('nelagala-style', get_template_directory_uri() . '/inc/nelagala/css/styles.css');
}
add_action('wp_enqueue_scripts', 'custom_theme_styles');
function custom_theme_scripts() {
    wp_enqueue_script('nelagala-script', get_template_directory_uri() . '/inc/nelagala/js/script.js', array(), false, true);
}
add_action('wp_enqueue_scripts', 'custom_theme_scripts');

// get_header();
?>

<section  class="sections default_page">
	<div class="container">
    <div class="nelagala-event">
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

<h1>Biography for <?php echo esc_html($participant_name); ?></h1>

<?php if (!empty($matched_events)) : ?>
    <?php foreach ($matched_events as $year => $events) : ?>
        <?php if ($year == $upcomingEventYear) : ?>
            <h2>Upcoming Event (<?php echo esc_html($year); ?>)</h2>
        <?php else : ?>
            <h2>Past Events (<?php echo esc_html($year); ?>)</h2>
        <?php endif; ?>

        <ul>
            <?php foreach ($events as $event) : ?>
                <li>
                    <a href="<?php echo esc_url($event['event_link']); ?>"><?php echo esc_html($event['event_title']); ?></a> - Role: <?php echo esc_html($event['role']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
<?php else : ?>
    <p>No event participation information found for this participant.</p>
<?php endif;


?> 

<nav class="event-navigation">
    <ul>
        <li><a href="index.html#about-the-event">About the Event</a></li>
        <li><a href="index.html#event-roles">Who's Who</a></li>
        <li><a href="index.html#honorees">Honorees</a></li>
        <li><a href="index.html#tickets">Tickets</a></li>
        <li><a href="index.html#lodging">Lodging</a></li>
        <li><a href="index.html#sponsorhips">Sponsorship Packages</a></li>
        <li><a href="index.html#advertising">Advertising Rates</a></li>
    </ul>

    <button class="hamburger" aria-label="Open navigation menu">
        <span></span>
        <span></span>
        <span></span>
    </button>
</nav>

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
        <h2>Honorary Event Chair</h2>
        <div>
            <img src="img\faces\zappia.jpg">
            <div>
                <p class="full-name">Ambassador Mariangela Zappia</p>
                <p class="personal-title"> Italian Ambassador to the United States of America</p>

            </div>
        </div>
        <article>
            <p>Mariangela Zappia is the Italian Ambassador to the United States of America. A career diplomat
                with
                over thirty-five years of experience, she is the first woman in her country to hold this
                position,
                as she was the first woman Permanent Representative of Italy to the United Nations in New York
                and
                to the North Atlantic Treaty Organization (NATO). She was also the first woman Diplomatic
                Advisor to
                the Italian Prime Minister and G7/G20 Sherpa.
            </p>
            <p>
                Additionally, Ambassador Zappia has served as Head of the European Union Delegation to the UN
                and
                other International Organizations, and Ministry Plenipotentiary at the Permanent Mission of
                Italy to
                the UN and other International Organizations, both in Geneva, Switzerland. She has also served
                as
                First Counsellor at the Permanent Mission of Italy to the UN in New York in the early 2000s and
                First Counsellor at the Italian Embassy in Brussels throughout the late 1990s.
            </p>
            <p>
                She holds a Master’s degree in Political Science and International Relations from the University
                of
                Florence, as well as a post-graduate degree in Diplomatic and International Relations from the
                same.
                She has followed periodic high-level training courses on diplomatic practice and management at
                the
                Diplomatic Academy of the Italian Ministry of Foreign Affairs in Rome.
            </p>
            <p>
                She has published works on reforming the UN Security Council and on Italy’s contributions to UN
                peacekeeping operations, and is a regular speaker at international conferences regarding Italian
                foreign policy, multilateral and global issues.
            </p>
            <p>
                She is an active member of the International Gender Champions Network aimed at promoting gender
                parity and women’s participation in decision-making. In 2019, she was awarded with the “Mela
                d’Oro”
                (Golden Apple) by the “Fondazione Marisa Bellisario” in recognition of her contribution to the
                advancement of women in public institutions.
            </p>
            <p>
                She has been awarded the decoration of “Commendatore” (Commander) of the Order of Merit of the
                Italian Republic. She speaks fluent English and French, and has a good knowledge of Spanish.
                She’s
                the mother of Claire, 28, and Christian, 24.
            </p>
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