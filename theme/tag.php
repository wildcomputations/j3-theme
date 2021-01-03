<?php
/**
 * Template for tag pages
 *
 * @package j3Custom
 */

/* Wordpress thinks categories have higher precendence than post formats. We 
 * want post format to dictate the layout, so brute force choosing the gallery 
 * layout. If we ever use archives for other post formats, we'll need to generalize.
 */
/*if (j3IsGalleryFormat()) {
    get_template_part('taxonomy-post_format', 'post-format-gallery');
} else {*/

require 'side-bars.php';

function j3TopicTitle() {
    echo '<h1 class="topicTitle">';
    single_tag_title(); 
    echo '</h1>';
}

function recentPosts()
{
    if (is_paged()) {
        j3PageNav("", "", $standalone = true); 
    }

    if ( have_posts() ) { 
        while ( have_posts() ) { 
            the_post(); 
            get_template_part( 'excerpt', get_post_format() ); 
        }
    } else { 
        get_template_part( 'content', 'none' ); 
    } 
}

function description()
{
    $descr_txt = tag_description();
    if (!empty($descr_txt)) {
        echo '<article class="visualPage">';
        echo $descr_txt;
        echo '</article>';
    }
}

get_header();

$tag = get_query_var('tag');

?>
<div class="main twoColumn">
    <div class="leftColumn">
<?php j3TopicTitle(); ?>
    </div>
    <div class="leftColumn">
    <?php recentPosts(); ?>
    </div> <!-- leftColumn -->
    <div class="rightColumn">
    <?php description(); ?>
    <?php j3RecentGalleries(NULL, $tag); ?>
    </div>
    <div class="leftColumn">
<?php j3PageNav("", "", $standalone = true); ?>
    </div>
</div><!--main-->

<?php get_footer();
/*}*/ ?>

