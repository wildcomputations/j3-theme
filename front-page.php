<?php
/**
 * Template file for the front page
 *
 * @package j3Custom
 */

function j3FrontRecentPosts()
{
    $displayNum = 3;

    $taxOnlyStd = array( array(
            'taxonomy' => 'post_format',
            'field' => 'slug',
            'terms' => array( 'post-format-gallery', 'post-format-image' ),
            'operator' => 'NOT IN',
        ) );

    $query = new WP_Query(array(
        'posts_per_page' => $displayNum ,
        'orderby' => 'ID',
        'tax_query' => $taxOnlyStd ));

    $result = "";
    // The Loop
    if ( $query->have_posts() ) {
        echo '<div class="hasPage leftColumn">
            <h1 class="topicTitle">Recent Posts</h1>';
        while ( $query->have_posts() ) {
                $query->the_post();
                get_template_part( 'excerpt', get_post_format() ); 
        }
        echo '<div class="center">
              <a href="' . get_permalink( get_option( 'page_for_posts' ) ) . 
             '">All Posts ... </a>
              </div>';

        echo '</div>';
    } 

    /* Restore original Post Data */
    wp_reset_postdata(); 
}

function j3RecentGalleries()
{
    $args = array(
        'post_type' => 'post',
        'orderby' => 'ID',
        'tax_query' => array(
            array(
                'taxonomy' => 'post_format',
                'field' => 'slug',
                'terms' => array( 'post-format-gallery' )
            )
        ),
        'posts_per_page' => 5
    );
    $query = new WP_Query( $args );
    if ($query->have_posts()) {
        echo '<div class="rightColumn">
            <h1 class="topicTitle">Recent Photo Albums</h1>';
        while ( $query->have_posts() ) {
                $query->the_post();
                set_query_var('display_post', 'summary');
                get_template_part( 'card', get_post_format() ); 
        }
        echo '<div class="albumSummary">
            <a href="' . get_post_format_link(get_post_format())
            . '">More Photos ...</a>
                </div> 
            </div>';
        /* Restore original Post Data */
        wp_reset_postdata();
    }
}

get_header(); ?>

<div class="main twoColumn"><!-- safari appears to not support main-->
    <?php while ( have_posts() ) : the_post(); ?>
    <div class="rightColumn">
        <article class="visualPage">
            <?php the_content(); ?>
        </article>
    </div>
    <?php endwhile; ?>
    <div class="leftColumn">
        <?php j3FancyPhoto(); ?>
    </div> 
    <?php j3FrontRecentPosts(); ?>
    <?php j3RecentGalleries(); ?>
</div><!--main-->

<?php get_footer(); ?>
