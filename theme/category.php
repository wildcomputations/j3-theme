<?php
/**
 * Template for category pages
 *
 * @package j3Custom
 */

/* Wordpress thinks categories have higher precendence than post formats. We 
 * want post format to dictate the layout, so brute force choosing the gallery 
 * layout. If we ever use archives for other post formats, we'll need to generalize.
 */
if (j3IsGalleryFormat()) {
    get_template_part('taxonomy-post_format', 'post-format-gallery');
} else {

require 'side-bars.php';

function j3TopicTitle($category) {
    $parent_slugs = array_diff(
        explode('|',
        get_category_parents($category->term_id, False, '|', True)),
        [$category->slug, '']
    );
    if ( $parent_slugs ) {
        echo '<h4 class="topicTitle">';
        $links = array();
        foreach ( $parent_slugs as $slug) {
            $parent = get_category_by_slug($slug);
            $url = get_category_link($parent);
            array_push($links,
                '<a href="' . $url . '">'
                . $parent->name . '</a>');
        }
        echo implode(' / ', $links) . '</h3>';
    }
    echo '<h1 class="topicTitle">';
    single_cat_title(); 
    echo '</h1>';
}

function j3HouseTempImg () {
    return;
    echo '
            <div class="dualShadow displayPhoto">
                <a href="/house/" class="photoLink">
                    <img src="/house/oneWeekTemps-basic.png" alt="latest temperatures"/>
                </a>
                <p>
                <a href="/house/">
                    Live temperature plots
                </a>
                </p>
            </div> ';
}

function recentPosts()
{
    if (is_paged()) {
        j3PageNav("", "", $standalone = true); 
    }

    if ( have_posts() ) { 
        while ( have_posts() ) { 
            the_post(); 
            if (get_post_type() == 'photo_album') {
                $format = 'gallery';
            } elseif (get_post_type() == 'attachment') {
                $format = 'image';
            } else {
                $format = get_post_format();
            }
            get_template_part( 'excerpt', $format ); 
        }
    } else { 
        get_template_part( 'content', 'none' ); 
    } 
}

function description()
{
    echo '<article class="visualPage">';
    echo category_description();
    echo '</article>';
}

get_header();

$cat = get_query_var('cat');
$fullCat = get_category ($cat);
$is_house = $fullCat->slug == "house";

?>
<div class="main twoColumn">
    <div class="leftColumn">
<?php j3TopicTitle($fullCat); ?>
    </div>
    <div class="rightColumn">
<?php 
if ($is_house) {
    j3HouseTempImg();
} else {
    j3RandomPhoto($fullCat->term_id); }
?>
    </div>
    <div class="leftColumn">
    <?php recentPosts(); ?>
    </div> <!-- leftColumn -->
    <div class="rightColumn">
    <?php description(); ?>
    <?php j3CtaBox(); ?>
    <?php j3RecentGalleries($fullCat->term_id); ?>
    </div>
    <div class="leftColumn">
<?php j3PageNav("", "", $standalone = true); ?>
    </div>
</div><!--main-->

<?php get_footer();
} ?>
