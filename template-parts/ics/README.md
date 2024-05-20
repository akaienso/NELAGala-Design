Add to functions.php:

```php
// Include the ICS download function and custom class
require_once get_template_directory() . '/inc/nelagala/template-parts/ics/ics-functions.php';
```

Add to Template:

```php
<?php
//echo '<h5>Location data</h5><pre>' . print_r($ng['nelagala_event_location'], true) . '</pre>';
if ($event_location) {

    // Loop over segments and construct HTML.
    $address = '';
    foreach (array('street_number', 'street_name', 'city', 'state', 'post_code', 'country') as $i => $k) {
        if (isset($event_location[$k])) {
            $address .= sprintf('%s, ', $k, $event_location[$k]);
        }
    }

    // Trim trailing comma.
    $address = trim($address, ', ');
    echo '<p>' . $address . '</p>';
}
// Add the link at the desired location in your template
if (is_singular('nelagala_event')) {
    echo '<a href="' . esc_url(add_query_arg('ics', '1', get_permalink())) . '">Add to Calendar</a>';
}
?>
```
