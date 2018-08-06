<?php
/**
 * @package j3Custom
 */
if (!function_exists('j3ContentExcerpt') ) :

// Everything that goes in the article
function j3ContentExcerpt() {
    echo '<article class="visualPage postExcerpt ">';

    j3PostThumbnail('medium', true);
    echo '<h1 class="articleTitle"><a href="' . get_permalink() . '">';
    the_title();
    echo '</a></h1>';
    if (get_post_type() == 'post' ) {
        echo '<div class="date">';
        $trip_date = j3_date_post('F j, Y');
        if (!empty($trip_date)) {
            echo '<b>' . $trip_date . '</b><br>';
        }
        echo '<b>' . get_the_author() . '</b><br>updated ';
        the_modified_date();
        echo '</div>';
    }
    the_excerpt();
    echo '<p><a class="moretag" href="'. get_permalink() . '"> Read Full Post</a></p>';
    echo '</article>';
}

endif; // function declarations

if ( post_password_required() ) {
    echo '<p>This post requires authentication</p>';
}

j3ContentExcerpt();
?>
