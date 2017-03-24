<?php
/**
 * The template file for the start page.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package j3Custom
 */

get_header(); ?>

<div class="main"><!-- safari appears to not support main-->
<?php 
j3FancyHeader(); 
if ( have_posts() ) { 
    while ( have_posts() ) { 
        the_post(); 
        get_template_part( 'full', get_post_format() ); 
    }
} else { 
    get_template_part( 'content', 'none' ); 
} 
j3PageNav("", "", $standalone = true); ?>
</div><!--main-->

<?php get_footer(); ?>
