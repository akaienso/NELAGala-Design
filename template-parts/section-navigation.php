<?php
global $is_demo_mode;
$ng_data = apply_filters('nelagala_template_data_navigation', []);
$path = $args['path'] ?? '';
$event_year = $args['event_year'] ?? '';

// Assuming the landing page has a specific characteristic, e.g., it's the homepage
// Adjust the condition based on your actual landing page check
$is_landing_page = (untrailingslashit($path) === '');

$base_url = $is_landing_page ? '' : "/nelagala/";

$osdia_icon = $ng_data['osdia_icon'] ?? null;
$nela_gala_icon = $ng_data['nela_gala_icon'] ?? null;
?>

<!-- SECTION: Navigation Sidebar -->
<nav class="event-navigation nav bg_shape<?=$is_demo_mode ? ' demo_mode' : '' ?>">
    <div class="burger-container">
        <button id="burger" aria-label="Open navigation menu">
            <span class="bar topBar"></span>
            <span class="bar btmBar"></span>
        </button>
        <p><a href="#top">Save the Date</a></p>
        <a href="/"<?= !empty($osdia_icon) ? '' : ' class="menu-item"'; ?>>
            <?php if (!empty($osdia_icon)) : ?>
                <img src="<?= esc_url($osdia_icon['url']); ?>" alt="<?= esc_attr($osdia_icon['alt']); ?>" title="Return to Order Sons and Daughters of Italy in America" />
            <?php else : ?>
                OSDIA
            <?php endif; ?>
        </a>
    </div>
    <ul class="menu">
        <?php
        // Initialize the array to track missing sections
        $missing_sections = [];

        $sections = [
            '#about-the-event' => [
                'content_field' => 'nelagala_event_title',
                'headline_field' => 'about_sidebar_link_label',
            ],
            '#event-roles' => [
                'content_field' => 'roles',
                'headline_field' => 'roles_sidebar_link_label',
            ],
            '#honorees' => [
                'content_field' => 'honorees',
                'headline_field' => 'honorees_sidebar_link_label',
            ],
            '#tickets' => [
                'content_field' => 'ticket_prices',
                'headline_field' => 'tickets_sidebar_link_label',
            ],
            '#lodging' => [
                'content_field' => 'lodging',
                'headline_field' => 'lodging_sidebar_link_label',
            ],
            '#sponsorships' => [
                'content_field' => 'sponsorship_packages',
                'headline_field' => 'sponsorship_sidebar_link_label',
            ],
            '#advertising' => [
                'content_field' => 'advertising_rates',
                'headline_field' => 'advertising_sidebar_link_label',
            ]
        ];
        foreach ($sections as $anchor => $fields) :
            $headline = $ng_data[$fields['headline_field']] ?? null;
            $content = $ng_data[$fields['content_field']] ?? null;
            if (!empty($headline) && !empty($content)) : 
                $link = $is_landing_page ? $anchor : $base_url . ltrim($anchor, '#');
                ?>
                <li class="menu-item"><a href="<?= esc_url($link); ?>"><?= esc_html($headline); ?></a></li>
            <?php endif;
        endforeach; ?>
        <li class="<?= !empty($nela_gala_icon) ? 'home' : 'menu-item'; ?>">
            <a href="<?= $base_url; ?>#top">
                <?php if (!empty($nela_gala_icon)) : ?>
                    <img src="<?= esc_url($nela_gala_icon['url']); ?>" alt="<?= esc_attr($nela_gala_icon['alt']); ?>" title="<?= esc_attr($nela_gala_icon['caption']); ?>" />
                <?php else : ?>
                    Home
                <?php endif; ?>
            </a>
        </li>
    </ul>
</nav>
<!-- !SECTION: Navigation Sidebar -->