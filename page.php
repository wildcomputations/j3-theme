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

get_header(); ?>

<div class="main"><!-- safari appears to not support main-->
    <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>


<div class="hgroup hasPage">
    <h1 class="topicTitle">
        <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?></a>
    </h1>
    <div class="rightContent">
        <article class="visualPage">
        <?php j3PostThumbnail(); ?>
            <?php the_content(); ?>
        </article>
    </div> <!-- rightContent -->
</div> <!-- hgroup -->

    <?php endwhile; else : 
    get_template_part( 'content', 'none' ); 
    endif; ?>

</div><!--main-->

<?php get_footer(); ?>
