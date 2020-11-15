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

function j3GalleryLinks() {
    $catHtml = j3AsideCategories();
    $trip_date_html = j3AsideCalendar();
    echo '<div class="linkBlock">
              <h1>Read More</h1>
              <ul>';
    if ($trip_date_html) {
        echo $trip_date_html;
    }
    echo '<li><a href="';
    echo get_post_format_link( get_post_format() );
    echo '">Explore all photo albums</a></li>';
    if ($catHtml) {
        echo $catHtml;
    }
    echo '</ul></div> <!--linkBlock-->';
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
<?php j3GalleryLinks(); ?>
    </aside>
</div> <!-- hgroup -->

