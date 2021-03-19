<?php
/**
 * @package j3Custom
 */
if (has_post_thumbnail()) {
    the_post_thumbnail("full", array('class' => 'displayPhoto'));
} elseif (get_post_type() == 'attachment') {
    echo wp_get_attachment_image( get_the_ID(), 'full',
        "",
        array('class' => 'attachment-full size-full wp-post-image displayPhoto')
    );
} else {
    echo "Image needs photo";
}

echo '<div class="displayPhoto photoData">
        <p>';
the_title();
echo '</p><p class="author">Photographer: ';
the_author();
echo '</p>';
$trip_date = j3_date_post('M j, Y');
if (!empty($trip_date)) {
    echo '<p class="date">' . $trip_date . '</p>';
}
echo '</div>';

/* TODO: Add comments, and link back to main post if attachment */
?>
