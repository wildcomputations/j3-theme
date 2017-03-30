<?php
/**
 * @package j3Custom
 */

if (! function_exists('j3GalleryExcerpt' ) ):
function j3GalleryExcerpt() 
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
                <div class="date">' . j3_date_post('M j, Y') . '</div>';
    the_excerpt();
    echo ' </article>';
}

endif;
?>


<div class="hgroup hasPage">
    <div class="rightContent">
        <?php
            j3GalleryExcerpt();
        ?>
    </div> <!-- rightContent -->
    <aside>
        <div class="linkBlock">
        <a href="<?php echo get_post_format_link( get_post_format() ) ?>">All Galleries </a>
        </div><!-- linkBlock -->
    </aside>
</div> <!-- hgroup -->


