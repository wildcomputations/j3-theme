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

require 'fancy-photo.php';

get_header(); ?>

<div class="main"><!-- safari appears to not support main-->
<?php 
j3FancyHeader(); 
if ( have_posts() ) { 
    while ( have_posts() ) { 
        the_post(); 
        get_template_part( 'excerpt', get_post_format() ); 
    }
} else { 
    get_template_part( 'content', 'none' ); 
} 
j3PageNav("", "", $standalone = true); ?>
</div><!--main-->

<?php get_footer();
} ?>
