<nav class="event-navigation nav">
    <div class="burger-container">
        <button id="burger" aria-label="Open navigation menu">
            <span class="bar topBar"></span>
            <span class="bar btmBar"></span>
        </button>
    </div>
    <ul class="menu">
        <?php foreach ($sections as $anchor => $field_key) : ?>
            <?php
            // Check if the field has content or rows (for repeaters)
            $value = get_field($field_key);
            if ((is_array($value) && !empty($value)) || (!is_array($value) && !empty($value))) : ?>
                <li class="menu-item"><a href="<?= $anchor ?>"><?= ucfirst(str_replace('-', ' ', substr($anchor, 1))) ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</nav>