<?php
/**
 * @package j3Custom
 *
 * Full view for gallery posts
 *
 * Date archives get day of week, and day of month.
 * Everything else gets year and month
 */


if (! function_exists('j3GallerySummary' ) ):
/* Display a gallery post as a stack of photos */
function j3GallerySummary($echo = true) 
{
    $result = '<div class="albumSummary">
        <div class="stackPhoto">
            <a href="' . get_permalink() . '" class="photoLink">';
    $result .= get_the_post_thumbnail(null, 'thumbnail' );
    $result .= '  </a>
            </div> <!-- stacks-->
            <p class="date">';
    if (is_front_page()) {
        $result .= get_the_date('M j');
    } else {
        $result .= get_the_date('D j');
    }
    $result .= '</p>
            <h1><a href="' . get_permalink() . '"> ' 
                . get_the_title()
                . '</a></h1>
                </div>';
    if ($echo) {
        echo $result;
    } else {
        return $result;
    }
}
endif;
?>

<?php
    j3GallerySummary(True);
?>
