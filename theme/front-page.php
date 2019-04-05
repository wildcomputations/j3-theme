<?php
/**
 * Template file for the front page
 *
 * @package j3Custom
 */

require 'side-bars.php';

function j3FrontRecentPosts()
{
    $displayNum = 4;

    $taxOnlyStd = array( array(
            'taxonomy' => 'post_format',
            'field' => 'slug',
            'terms' => array( 'post-format-gallery', 'post-format-image' ),
            'operator' => 'NOT IN',
        ) );
    $query = new WP_Query(array(
        'posts_per_page' => $displayNum ,
        'tax_query' => $taxOnlyStd,
        ));

    $result = "";
    // The Loop
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
                $query->the_post();
                get_template_part( 'excerpt', get_post_format() ); 
        }
        echo '<div class="aligncenter">
              <a href="' . get_permalink( get_option( 'page_for_posts' ) ) . 
             '">All Posts ... </a>
              </div>';
    } 

    /* Restore original Post Data */
    wp_reset_postdata(); 
}

get_header(); ?>

<div class="main twoColumn"><!-- safari appears to not support main-->
    <div class="rightColumn">
    <?php j3RandomPhoto(); ?>
    </div>
    <div class="leftColumn">
    <?php j3FrontRecentPosts(); ?>
    </div>
    <div class="rightColumn">
    <?php while ( have_posts() ) : the_post(); ?>
    <article class="visualPage">
        <?php the_content(); ?>
    </article>
    <?php endwhile; ?>
    <?php j3CtaBox(); ?>
    <?php j3RecentGalleries(); ?>
    </div>
</div><!--main-->

<?php get_footer(); ?>
