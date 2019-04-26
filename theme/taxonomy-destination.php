<?php
/**
 * Template for destination pages
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

function j3ArticleTitle() {
    echo '<h1 class="articleTitle">';
    single_term_title();
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

function random_photo_thumbnail($term_id)
{
    $photo_meta = j3RandomPhotoSearch($term_id, 'destination');
    if (!is_null($photo_meta)) {
        echo wp_get_attachment_image(
            $photo_meta['id'],
            'large',
            "",
            array('class' => 'wp-post-image')
        );
    }
}

function children($term_id)
{
    $taxonomy_name = 'destination';
    $term_children = get_term_children( $term_id, $taxonomy_name );
    if (count($term_children) == 0) return;

    echo "<h2>Sub-Destinations</h2>";
    echo '<ul>';
    foreach ( $term_children as $child ) {
        $term = get_term_by( 'id', $child, $taxonomy_name );
        echo '<li><a href="' . get_term_link( $child, $taxonomy_name ) . '">' . $term->name . '</a></li>';
    }
    echo '</ul>';
}

function contentArticle($term)
{
    echo '<article class="visualPage">';
    random_photo_thumbnail($term->term_id, 'destination');
    j3ArticleTitle();
    echo term_description($term->term_id, 'destination');
    if ($term->parent != 0) {
        $parent = get_term($term->parent, 'destination');
        echo "<p>This destination is part of ";
        echo '<a href="'.get_term_link($parent).'">';
        echo $parent->name."</a></p>";
    }
    children($term->term_id);
}

get_header();

$term_str = get_query_var('destination');
$term_obj = get_term_by('slug', $term_str, 'destination');

?>
<div class="main twoColumn">
    <div class="leftColumn">
        <?php contentArticle($term_obj); ?>
    </div>
    <div class="leftColumn">
    <?php j3PageNav("", "", $standalone = true); ?>
    </div>
    <div class="rightColumn">
    <?php j3RecentGalleries($term_obj->term_id, 'destination'); ?>
    </div>
    <div class="leftColumn">
        <?php recentPosts(); ?>
    </div>
    <div class="leftColumn">
    <?php j3PageNav("", "", $standalone = true); ?>
    </div>
</div><!--main-->

<?php get_footer();
} ?>

