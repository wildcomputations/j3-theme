<?php
/**
 * @package j3Custom
 *
 * Full view for gallery posts
 */

require 'full-functions.php';


if (! function_exists('j3GalleryFull' ) ):
function j3GalleryFull() 
{
    echo '<article class="albumFull hasStack visualPage">';
    j3ArticleHead(True);
    the_content();
    j3ArticleFooter();
    echo '</article>';

    if (comments_open()) {
        echo '<article class="subPage">';
        comments_template();
        echo '</article>';
    }
}

endif;
?>

<div class="hgroup hasPage">
    <div class="rightContent">
        <?php
            j3GalleryFull();
        ?>
    </div> <!-- rightContent -->
    <aside class=leftBar>
<?php 
      j3AsideSkipToComments();
      j3AsideArticleLinks(); 
      j3AsideMapLinks();
      j3AsideCtaWidgets();
?>
    </aside>
</div> <!-- hgroup -->

