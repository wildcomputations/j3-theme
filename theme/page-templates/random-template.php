<?php
/**
 * Template Name: Random Single
 *
 * @package j3Custom
 */

get_header();

$taxOnlyStd = array( array(
        'taxonomy' => 'post_format',
        'field' => 'slug',
        'terms' => array( 'post-format-gallery', 'post-format-image' ),
        'operator' => 'NOT IN',
    ) );
$query = new WP_Query(array(
    'posts_per_page' => 1 ,
    'orderby' => 'rand',
    'tax_query' => $taxOnlyStd,
    ));

// Really just want to run single.php for the rest of this
get_header(); ?>

<div class="main"><!-- safari appears to not support main-->
<?php 
if ( $query->have_posts() ) { 
    while ( $query->have_posts() ) { 
        $query->the_post(); 
        if (get_post_type() == 'photo_album') {
            $format = 'gallery';
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

<?php get_footer();

/* Restore original Post Data */
wp_reset_postdata(); 

?>
