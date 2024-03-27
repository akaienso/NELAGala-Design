                        <!--  SECTION: About the Event -->
                        <section id="about-the-event">
                            <article class="<?= $display_theme_sidebar ? '' : 'full-width'; ?>">
                                <h2><?php echo $about_section_headline; ?></h2>
                                <?php echo the_content(); ?>
                            </article>
                            <?php if ($display_theme_sidebar) : ?>

                                <!-- NOTE: Theme Sidebar Content -->
                                <?php if ($theme_title && $theme_content) : ?>
                                    <aside>
                                        <?php if (!empty($theme_image)) : ?>
                                            <img src="<?php echo esc_url($theme_image['url']); ?>" alt="<?php echo esc_attr($theme_image['alt']); ?>" title="<?php echo esc_attr($theme_image['caption']); ?>" />
                                        <?php endif; ?>
                                        <h2><?php echo $theme_title; ?></h2>
                                        <?php echo $theme_content; ?>
                                    </aside>
                            <?php endif;
                            endif; ?>

                        </section>
                    <?php if ($display_promotional_video) : ?>
                        <iframe class="video" src="<?php echo  $promotional_video; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    <?php endif; ?>
                        <!--  !SECTION: About the Event -->