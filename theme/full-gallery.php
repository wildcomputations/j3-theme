<?php
/**
 * @package j3Custom
 *
 * Full view for gallery posts
 */

require 'full-functions.php';

function _j3theme_gallery_links_inline() {
    if (!function_exists('j3gallery_get_referrers')) {
        return;
    }
    $referring_ids = j3gallery_get_referrers(get_the_ID());
    if (!$referring_ids) {
        return;
    }
    $links = [];
    foreach ($referring_ids as $full_post_id) {
        $parent_url = get_permalink($full_post_id);
        $parent_title = get_the_title($full_post_id);
        array_push($links, 
            '<a href="' . $parent_url . '">' . $parent_title . '</a>');
    }
    echo '<p>This gallery featured in '
        . implode(' and ', $links) . '.</p>';
}

function _j3theme_gallery_links_sidebar() {
    if (!function_exists('j3gallery_get_referrers')) {
        return;
    }
    $referring_ids = j3gallery_get_referrers(get_the_ID());
    if (!$referring_ids) {
        return;
    }
    // kind of silly to have to do the query again, but get_template_part
    // requires the global post
    $query = new WP_Query(array('post__in'=> $referring_ids));
    if ($query->have_posts()) {
        echo '<div class="linkBlock"><h1>Full articles</h1>';

        while ( $query->have_posts() ) {
            $query->the_post();
            if (get_post_type() == 'photo_album') {
                $format = 'gallery';
            } else {
                $format = get_post_format();
            }
            get_template_part( 'card', $format ); 
        }
        echo '</div> <!--linkBlock-->';
    }
    /* Restore original Post Data */
    wp_reset_postdata(); 
}


if (! function_exists('j3GalleryFull' ) ):
function j3GalleryFull() 
{
    echo '<article class="albumFull hasStack visualPage">';
    j3ArticleHead(True);
    _j3theme_gallery_links_inline();
    the_content();
    j3ArticleFooter();
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
    <aside class=leftBar>
<?php 
      j3AsideSkipToComments();
      j3AsideArticleLinks(); 
      j3AsideMapLinks();
      _j3theme_gallery_links_sidebar();
      j3AsideCtaWidgets();
?>
    </aside>
    <aside class="mini">
        <a href="<?php the_permalink(); ?>">More details ...</a>
    </aside>
    <div class="rightContent">
<?php j3ContentComments(); ?>
    </div> <!-- rightContent -->
</div> <!-- hgroup -->
