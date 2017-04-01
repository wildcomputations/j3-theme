<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package j3Custom
 */

if (j3IsGalleryFormat()) {
    get_template_part('taxonomy-post_format', 'post-format-gallery');
} else {
get_header(); ?>

<div class="main"><!-- safari appears to not support main-->
<?php 
if ( have_posts() ) { 
    while ( have_posts() ) { 
        the_post(); 
        get_template_part( 'excerpt', get_post_format() ); 
    }
    j3PageNav("", "", $standalone = true);
} else { 
    get_template_part( 'content', 'none' ); 
}  ?>
</div><!--main-->

<?php get_footer();
}?>
