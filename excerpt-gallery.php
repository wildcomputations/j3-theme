<?php
/**
 * @package j3Custom
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

endif;
?>


<div class="hgroup hasPage">
    <div class="rightContent">
            <?php
            if (is_numeric(get_the_content()) ) {
                j3GalleryExcerpt();
            } else {
                j3GalleryExcerptNew();
            }
            ?>
    </div> <!-- rightContent -->
    <aside>
        <div class="linkBlock">
        <a href="<?php echo get_post_format_link( get_post_format() ) ?>">All Galleries </a>
        </div><!-- linkBlock -->
    </aside>
</div> <!-- hgroup -->


