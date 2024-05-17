<?php
$ng = apply_filters('nelagala_template_data_navigation', []);
$path = $args['path'] ?? '';
$event_year = $args['event_year'] ?? '';

$show_top_banner = $ng['show_top_banner'];
$top_banner_headline = $ng['top_banner_headline'];
$top_banner_content = $ng['top_banner_content'];
$top_banner_button_label = $ng['top_banner_button_label'];
$top_banner_button_link = $ng['top_banner_button_link'];
$top_banner_image = $ng['top_banner_image'];
$top_banner_image_link = $ng['top_banner_image_link'];
$top_banner_image_link_label = $ng['top_banner_image_link_label'];
$top_banner_background_color = $ng['top_banner_background_color'];
$top_banner_text_color = $ng['top_banner_text_color'];
$top_banner_headline_color = $ng['top_banner_headline_color'];
$top_banner_button_color = $ng['top_banner_button_color'];
$top_banner_button_text_color = $ng['top_banner_button_text_color'];
$top_banner_button_hover_color = $ng['top_banner_button_hover_color'];
$top_banner_button_text_hover_color = $ng['top_banner_button_text_hover_color'];
if ($show_top_banner) :
?>
    <!--  SECTION: Top Banner -->
    <style>
        /* Colors */
        #top-banner {
            background-color: <?php echo esc_attr($ng['top_banner_background_color']); ?>;
            max-width: 100%;
        }

        #top-banner article {
            color: <?php echo esc_attr($ng['top_banner_text_color']); ?>;
            max-width: 1100px;
            margin: auto;
        }

        #top-banner div.content>* {
            color: <?php echo esc_attr($ng['top_banner_text_color']); ?>;

            font-family: barlow, sans-serif;
            font-weight: 600;
            text-transform: normal;
            font-size: 1.1em;
            font-style: normal;
        }

        #top-banner div.content>h1 {
            color: <?php echo esc_attr($ng['top_banner_headline_color']); ?>;

            font-family: minion-pro-condensed-subhead, serif;
            font-weight: 800;
            font-style: italic;
            font-size: 2em;
            line-height: 1.125em;

            /* font-family: barlow, sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 1.25em;
            font-style: normal; */

            /* font-family: snell-roundhand,cursive;
            font-size: 2.4em; */
        }

        #top-banner .donate-button {
            background-color: <?php echo esc_attr($ng['top_banner_button_color']); ?>;
            color: <?php echo esc_attr($ng['top_banner_button_text_color']); ?>;
            transition: background-color 0.3s, color 0.3s;
        }

        #top-banner .donate-button:hover {
            background-color: <?php echo esc_attr($ng['top_banner_button_hover_color']); ?>;
            color: <?php echo esc_attr($ng['top_banner_button_text_hover_color']); ?>;
        }

        /* Layout */
        #top-banner {
            width: 100%;
            margin-top: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            gap: 20px;
        }

        #top-banner article {
            display: flex;
            flex: 1;
            flex-wrap: wrap;
            align-items: center;
            gap: 20px;
        }

        #top-banner img.logo {
            max-width: 100px;
            margin-right: 20px;
        }

        #top-banner h1 {
            flex: 1 1 100%;
            font-size: 2em;
            margin: 10px 0;
        }

        #top-banner .content {
            flex: 2;
        }

        #top-banner .cta {
            margin-left: auto;
        }

        #top-banner .donate-button {
            display: inline-block;
            padding: 10px 25px;
            text-decoration: none;
            border-radius: 5px;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            #top-banner {
                flex-direction: column;
                align-items: flex-start; 
                gap: 5px; 
            }

            #top-banner article {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px; 
            }

            #top-banner img.logo {
                max-width: 50%;
                margin: 0 0 10px 0;
            }

            #top-banner h1 {
                margin: 10px 0;
            }

            #top-banner .content {
                margin-bottom: 10px;
            }

            #top-banner .cta {
                margin: 0;
            }
        }
    </style>
    <section id="top-banner" class="full-width bg_shape">
        <article>
        <?php if (!empty($top_banner_image)) : ?>
            <?php if (!empty($top_banner_image_link)) : ?>
                <a href="<?php echo esc_url($top_banner_image_link); ?>" title="<?php echo esc_attr($top_banner_image_link_label); ?>">
            <?php elseif (!empty($top_banner_button_link)) : ?>
                <a href="<?php echo esc_url($top_banner_button_link); ?>" title="<?php echo esc_attr($top_banner_button_label); ?>">
            <?php endif; ?>
            <figure>
                <img src="<?php echo esc_url($top_banner_image['url']); ?>" alt="Sons of Italy Foundation Logo" class="logo"
                <?=!empty($top_banner_image['caption']) ? ' title="' . $top_banner_image['caption'] . '"' : ''; ?>>
                <?php if (!empty($top_banner_image['caption'])) : ?>
                    <figcaption hidden><?php echo esc_html($top_banner_image['caption']); ?></figcaption>
                <?php endif; ?>
            </figure>
            <?php if (!empty($top_banner_image_link) || !empty($top_banner_button_link)) : ?>
                </a>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (!empty($top_banner_headline) || !empty($top_banner_content)) : ?>
            <div class="content">
                <?php if (!empty($top_banner_headline)) : ?>
                    <h1><?php echo esc_html($top_banner_headline); ?></h1>
                <?php endif; ?>
                <?php echo wp_kses_post($top_banner_content); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($top_banner_button_link)) : ?>
            <div class="cta">
                <a href="<?php echo esc_url($top_banner_button_link); ?>" class="donate-button" target="_blank" rel="noopener noreferrer">
                    <?php echo esc_html($top_banner_button_label); ?>
                </a>
            </div>
        <?php endif; ?>
        </article>
    </section>
    <!--  !SECTION: Top Banner-->
<?php endif; ?>