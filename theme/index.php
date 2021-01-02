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
if ( is_paged() ) {
    j3PageNav("", "", $standalone = true);
}
if ( have_posts() ) { 
    while ( have_posts() ) { 
        the_post(); 
?>
<div class="hgroup hasPage">
    <div class="rightContent">
<?php
        if (get_post_type() == 'photo_album') {
            $format = 'gallery';
        } elseif (get_post_type() == 'attachment') {
            $format = 'image';
        } else {
            $format = get_post_format();
        }
 get_template_part( 'excerpt', $format ); ?>
    </div> <!-- rightContent -->
</div> <!-- hgroup -->

<?php

    }
    j3PageNav("", "", $standalone = true);
} else { 
    get_template_part( 'content', 'none' ); 
}  ?>
</div><!--main-->

<?php get_footer();
}?>
