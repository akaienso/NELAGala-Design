<?php
$ng_data = apply_filters('nelagala_template_data_navigation', []);
$path = $args['path'] ?? '';
$event_year = $args['event_year'] ?? '';
?>
<!--  SECTION: Tickets -->
<?php
if (have_rows('ticket_prices')) :
?>
    <section id="tickets" class="tickets">
        <h2><?php echo esc_html($ticket_section_headline) ?></h2>
        <?php if ($ticket_section_top_content) : ?>
            <span class="sub-text"><?php echo $ticket_section_top_content; ?></span>
        <?php endif; ?>
        <div class="packages">
            <?php
            while (have_rows('ticket_prices')) : the_row();
                $type = get_sub_field('type');
                $price = get_sub_field('price');
                $tax_deduction = get_sub_field('tax_deduction');
                $description = get_sub_field('description');
                $button_label = get_sub_field('cta_button_label');
                $button_url = get_sub_field('cta_button_link');
            ?>
                <div class="package">
                    <h2><?php echo esc_html($type); ?></h2>
                    <p>Price each: $<?php echo esc_html($price); ?></p>
                    <p>Allowable tax deduction: $<?php echo esc_html($tax_deduction); ?></p>
                    <p><?php echo ($description); ?></p>
                    <?php if ($button_label && $button_url) : ?>
                        <a href="<?php echo esc_url($button_url); ?>" target="_blank"><button><?php echo esc_html($button_label); ?></button></a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
<?php endif; ?>
<!--  !SECTION: Tickets -->