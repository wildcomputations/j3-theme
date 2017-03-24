<?php
/*
Template Name: Archives
This is a custom page template. Currently it is used for the "by year" page.
*/

get_header(); ?>

<div class="main"><!-- safari appears to not support main-->
    <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>

    <div class="hgroup hasPage">
        <div class="rightContent visualPage history hasStack">
            <?php the_content(); ?>
            <h1>Chronological Log of All Posts</h1>
            <ul>
            <?php wp_get_archives(array(
                'type' => 'yearly',
                'format' => 'custom',
                'before' => '<li><div class="stackBook">',
                'after' => '</div></li>'
            )); ?>
            </ul>
        </div>
    </div>

    <?php endwhile; else : ?>
        <div class="hgroup hasPage">
            <div class="rightContent visualPage">
                <h1>Archive Page Missing</h1>
            </div>
        </div>
    <?php endif; ?>

</div><!--main-->

<?php get_footer(); ?>
