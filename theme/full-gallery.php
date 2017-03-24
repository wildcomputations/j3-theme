<?php
/**
 * @package j3Custom
 *
 * Full view for gallery posts
 */


if (! function_exists('j3GalleryFull' ) ):
function j3GalleryFull() 
{
    echo '<article class="albumFull hasStack visualPage">
         <h1 class="articleTitle">'  . get_the_title() . '</h1>
         <div class="date">' . get_the_date('M j, Y') . '</div>';
    the_content();
    echo '</article>';
}

endif;
?>

<div class="hgroup hasPage">
    <div class="rightContent">
        <?php
            j3GalleryFull();
        ?>
    </div> <!-- rightContent -->
    <aside>
        <div class="linkBlock">
        <a href="<?php echo get_post_format_link( get_post_format() ) ?>">All Galleries </a>
        </div><!-- linkBlock -->
    </aside>
</div> <!-- hgroup -->

