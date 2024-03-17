<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <article class="participant-biography">
        <h1><?php the_title(); ?></h1>
        <?php
        $photo = get_field('photo');
        $title = get_field('title');
        $website = get_field('website');
        $summary = get_field('summary');
        ?>
        <?php if ($photo): ?>
            <img src="<?php echo esc_url($photo['url']); ?>" alt="<?php echo esc_attr($photo['alt']); ?>">
        <?php endif; ?>
        <?php if ($title): ?>
            <p><strong>Title:</strong> <?php echo esc_html($title); ?></p>
        <?php endif; ?>
        <?php if ($website): ?>
            <p><strong>Website:</strong> <a href="<?php echo esc_url($website); ?>" target="_blank">Visit</a></p>
        <?php endif; ?>
        <?php if ($summary): ?>
            <div class="summary">
                <?php echo wp_kses_post($summary); ?>
            </div>
        <?php endif; ?>
    </article>
<?php endwhile; endif; ?>

<?php get_footer(); ?>
