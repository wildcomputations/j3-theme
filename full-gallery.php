<?php
/**
 * @package j3Custom
 *
 * Full view for gallery posts
 */


if (! function_exists('j3GalleryFull' ) ):
function j3GalleryFull() 
{
    global $wpdb;
    $myGallData = $wpdb->get_row("SELECT galdesc from " . $wpdb->nggallery . " where gid = " . get_the_content() . ";");
    if (!$myGallData) {
        $description = "No such gallery";
    } else {
        $description = $myGallData->galdesc;
    }

    echo '<article class="albumFull hasStack visualPage">
         <h1 class="articleTitle">'  . get_the_title() . '</h1>
         <div class="date">' . get_the_date('M j, Y') . '</div>
         <p>' . $description . '</p>';
    echo do_shortcode( '[j3gallery id="' . get_the_content() . '"]');
    j3PostNav();
    echo '</article>';
}

function j3GalleryFullNew()
{
    echo '<article class="albumFull hasStack visualPage">
         <h1 class="articleTitle">'  . get_the_title() . '</h1>
         <div class="date">' . get_the_date('M j, Y') . '</div>';
    the_content();
    j3PostNav();
    echo '</article>';
}

endif;
?>

<div class="hgroup hasPage">
    <div class="rightContent">
            <?php
            if (is_numeric(get_the_content()) ) {
                j3GalleryFull();
            } else {
                j3GalleryFullNew();
            }
            ?>
    </div> <!-- rightContent -->
    <aside>
        <div class="linkBlock">
        <a href="<?php echo get_post_format_link( get_post_format() ) ?>">All Galleries </a>
        </div><!-- linkBlock -->
    </aside>
</div> <!-- hgroup -->

