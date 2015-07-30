<?php
/**
 * @package j3Custom
 *
 * Full view for gallery posts
 *
 * Date archives get day of week, and day of month.
 * Everything else gets year and month
 */


if (! function_exists('j3GallerySummaryNGG' ) ):
/* Display a gallery post as a stack of photos */
function j3GallerySummaryNGG($echo = True)
{
    if ( post_password_required() ) {
        $result =  '<div class="albumSummary">
              <p>Authentication required</p>
              </div>';
        if ($echo) {
            echo $result;
            return;
        } else {
            return $result;
        }
    }
    global $wpdb;
    $myGallData = $wpdb->get_row("SELECT path, previewpic from " . $wpdb->nggallery . " where gid = " . get_the_content() . ";");
    if (! $myGallData) {
        $picFilePath ="";
    } else {
        $myPicData = $wpdb->get_row("SELECT filename FROM " . $wpdb->nggpictures . " where pid = " . $myGallData->previewpic . ";");
        $picFilePath = site_url( $myGallData->path . '/thumbs/thumbs_' . $myPicData->filename );
    }

    $result = '<div class="albumSummary">
        <div class="stackPhoto">
            <a href="' . get_permalink() . '" class="photoLink">
            <img src="'. $picFilePath . '"
                 alt="' . get_the_title() . '"/>
            </a>
            </div> <!-- stacks-->
            <p class="date">' . get_the_date('D j') . '</p>
            <h1><a href="' . get_permalink() . '"> ' 
                . get_the_title()
                . '</a></h1>
                </div>';
    if ($echo == true) {
        echo $result;
    } else {
        return $result;
    }
}

function j3GallerySummaryNew($echo = true) 
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
    if (is_numeric(get_the_content()) ) {
        j3GallerySummaryNGG(True);
    } else {
        j3GallerySummaryNew(True);
    }
?>
