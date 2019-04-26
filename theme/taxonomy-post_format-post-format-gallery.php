<?php
/**
 * Template for gallery archives.
 *
 * @package j3Custom
 */
get_header(); ?>

<div class="main"><!-- safari appears to not support main-->
<?php 
if ( have_posts() ) { 
    j3PageNav("", "", $standalone = true);
    echo '<div class="hgroup hasPage">';
    echo '<div class="rightContent visualPage history hasStack">';
    while ( have_posts() ) { 
        the_post(); 
        get_template_part("card", get_post_format());
    }
    echo '</div></div>';
    j3PageNav("", "", $standalone = true);
} else { 
    get_template_part( 'content', 'none' ); 
}  ?>
</div><!--main-->

<?php get_footer();
?>

