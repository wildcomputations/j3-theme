<?php
/**
 * @package j3Custom
 */

/* To do/consider
 * - auto generation of posts should set post title and fill in content and excerpt as below
 * - change this to a filter on get_content() so that RSS feed gets the title and description too
 * - figure out what wp_link_pages does
 * - sanity checking on get_the_content() ( also fixed by auto generated post)
 * - improve css, stacked photo frames, etc
 */

if (! function_exists('j3GalleryExcerpt' ) ):
function j3GalleryExcerpt() 
{
    global $wpdb;
    $myGallData = $wpdb->get_row("SELECT path, previewpic, galdesc from " . $wpdb->nggallery . " where gid = " . get_the_content() . ";");
    if (! $myGallData) {
        $picFilePath ="";
        $description = "No such gallery";
    } else {
        $myPicData = $wpdb->get_row("SELECT filename FROM " . $wpdb->nggpictures . " where pid = " . $myGallData->previewpic . ";");
        $picFilePath = site_url( $myGallData->path . '/thumbs/thumbs_' . $myPicData->filename );
        $description = $myGallData->galdesc;
    }

    echo '<article class="albumExcerpt hasStack visualPage">
          <div class="stackPhoto">
            <a href="' . get_permalink() . '" class="photoLink">
            <img src="'. $picFilePath . '"
                 alt="' . get_the_title() . '"/>
            </a>
            </div> <!-- stacks-->
            <h1 class="articleTitle"><a href="' . get_permalink() . '"> ' 
                . get_the_date('Y-m-d') . " " 
                . get_the_title()
            . '</a></h1>
            <p>' . $description . '</p>
           </article>';
}

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

function j3GalleryExcerptNew() 
{
    echo '<article class="albumExcerpt hasStack visualPage">
          <div class="stackPhoto">
            <a href="' . get_permalink() . '" class="photoLink">';
    if (has_post_thumbnail()) {
        the_post_thumbnail( 'thumbnail' );
    } else {
        echo "Album needs summary photo";
    }
    echo '  </a>
            </div> <!-- stacks-->
            <h1 class="articleTitle"><a href="' . get_permalink() . '">' 
                . get_the_title() . '</a></h1>
                <h2>Photo Album</h2>
                <div class="date">' . get_the_date('M j, Y') . '</div>';
    the_excerpt();
    echo ' </article>';
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

<?php
if (get_query_var('display_post') == 'summary') {
    if (is_numeric(get_the_content()) ) {
        j3GallerySummary();
    } else {
        j3GallerySummaryNew();
    }
} else { 
    $useExcerpt = (get_query_var('display_post') == "excerpt")
        || !is_single();
?>
<div class="hgroup hasPage">
    <div class="rightContent">
            <?php
            if (is_numeric(get_the_content()) ) {
                if ( $useExcerpt ) {
                    j3GalleryExcerpt();
                } else {
                    j3GalleryFull();
                }
            } else {
                if ( $useExcerpt ) {
                    j3GalleryExcerptNew();
                } else {
                    j3GalleryFullNew();
                }
            }
            ?>
    </div> <!-- rightContent -->
    <aside>
        <div class="linkBlock">
        <a href="<?php echo get_post_format_link( get_post_format() ) ?>">All Galleries </a>
        </div><!-- linkBlock -->
    </aside>
</div> <!-- hgroup -->
<?php } ?>

