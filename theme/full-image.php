<?php
/**
 * @package j3Custom
 */
require 'full-functions.php';
if (has_post_thumbnail()) {
    $trip_date = j3_date_post('M j, Y');
    $img_code = get_the_post_thumbnail(null, "full",
        // this class looses out to .wp-post-image which is added by default
        array('class' => 'wp-image'));
    $post_link = "";
} elseif (get_post_type() == 'attachment') {
    $parent_obj = get_post(wp_get_post_parent_id(get_the_ID()));
    $trip_date = j3_date_post('M j, Y', $parent_obj);
    $img_code = wp_get_attachment_image( get_the_ID(), 'full', "",
        array('class' => 'wp-image'));
    $post_link = "<a href=" . get_permalink($parent_obj) .
        ">" . get_the_title($parent_obj) . "</a>";
} else {
    $trip_date ="";
    $img_code = "<p>Image needs photo</p>";
    $post_link = "";
}


echo '<article class="visualPage">';
echo '<h3>Photo: ';
the_title();
echo '</h3><p>';
if (!empty($post_link)) {
    echo '<p>This picture is from: ' . $post_link . '</p>';
}
if (!empty($trip_date)) {
    echo '<p class="date">Date: ' . $trip_date . '</p>';
}
echo $img_code;
echo '</article>';

echo '<div class="hgroup hasPage">';
echo '<div class="rightContent">';
j3ContentComments();
echo '</div> <!-- rightContent -->';
echo '</div> <!-- hgroup -->';

?>
