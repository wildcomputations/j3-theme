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

function j3TopicTitle() {
    echo '<h1 class="topicTitle">';
    single_cat_title(); 
    echo '</h1>';
}

function j3HouseTempImg () {
    echo '
            <div class="dualShadow displayPhoto">
                <a href="/house/" class="photoLink">
                    <img src="/house/oneWeekTemps-basic.png" alt="latest temperatures"/>
                </a>
                <p>
                <a href="/house/">
                    Live temperature plots
                </a>
                </p>
            </div> ';
}


require 'side-bars.php';


get_header();

$cat = get_query_var('cat');
$fullCat = get_category ($cat);
$is_house = $fullCat->slug == "house";

?>
<div class="main twoColumn">
    <div class="leftColumn">
<?php j3TopicTitle(); ?>
    </div>
    <div class="rightColumn">
<?php 
if ($is_house) {
    j3HouseTempImg();
} else {
    j3RandomPhoto($fullCat->term_id); }
?>
    </div>
    <div class="leftColumn">
<?php if (is_paged()) j3PageNav("", "", $standalone = true); 

if ( have_posts() ) { 
    while ( have_posts() ) { 
        the_post(); 
        get_template_part( 'excerpt', get_post_format() ); 
    }
} else { 
    get_template_part( 'content', 'none' ); 
} 
?>
    </div> <!-- leftColumn -->
    <div class="rightColumn">
    <?php j3CtaBox(); ?>
    <?php j3RecentGalleries($fullCat->term_id); ?>
    </div>
    <div class="leftColumn">
<?php j3PageNav("", "", $standalone = true); ?>
    </div>
</div><!--main-->

<?php get_footer();
} ?>
