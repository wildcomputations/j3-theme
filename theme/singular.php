<?php
/**
 * Display one post
 *
 * @package j3Custom
 */

get_header(); ?>

<div class="main"><!-- safari appears to not support main-->
<?php 
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
        get_template_part( 'full', $format ); 
    }
} else { 
    get_template_part( 'content', 'none' ); 
} 
j3PageNav("", "", $standalone = true); ?>
</div><!--main-->

<?php get_footer(); ?>
