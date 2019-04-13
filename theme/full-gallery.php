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
    echo '<article class="albumFull hasStack visualPage">
         <h1 class="articleTitle">'  . get_the_title() . '</h1>';
    echo '<div class="date">';
    $trip_date = j3_date_post('F j, Y');
    if (!empty($trip_date)) {
        echo '<b>' . $trip_date . '</b><br>';
    }
    echo '</div>';
    the_content();
    echo '</article>';
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

