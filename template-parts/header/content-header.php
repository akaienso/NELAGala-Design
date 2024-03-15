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
