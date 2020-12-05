<?php

/* 
Template Name: Search

I am not using the default search.php from the template hierarchy because I 
disagree with wordpress about when it should display that page vs category 
pages.
 */

function autoCheck($meta, $label)
{
    echo '<label>
        <input type="radio" ' . $meta 
        . ' onChange="this.form.submit()">'
        . $label . '
        </label><br>';
}

function generateCategoryCheckboxes()
{
/* documenation:
    https://codex.wordpress.org/WordPress_Query_Vars
    these need to generate a query like ?cat=1,2 
 */
    autoCheck('name="cat" value="" ' . checked(true, false), "All");
    $categories = get_categories();
    foreach ($categories as $cat) {
        autoCheck('name="cat" value="'
            . $cat->cat_ID . '" '
            . checked($cat->cat_ID, get_query_var("cat"), false),
            $cat->name);
    }
}

function generatePostTypes()
{
    autoCheck('name="post_type" value="" ' . checked(true, false), "All");
    autoCheck('name="post_type" value="post" '
        . checked("post", get_query_var("post_type"), false),
            "Trip Reports");
    autoCheck('name="post_type" value="photo_album" '
        . checked("photo_album", get_query_var("post_type"), false),
            "Photo Albums");
    autoCheck('name="post_type" value="page" '
        . checked("page", get_query_var("post_type"), false),
            "Pages");
    autoCheck('name="post_type" value="attachment" '
        . checked("attachment", get_query_var("post_type"), false),
            "Photos");
}

function searchmap()
{
    if (! function_exists('cttm_scripts_frontend') ) return;
?>
<div class="hgroup hasPage">
    <div class="rightContent">
        <div class="visualPage">
<?php echo do_shortcode('[travelers-map current_query_markers=true height=50vh max_cluster_radius=12]'); ?>
        </div> <!-- visualPage -->
    </div> <!-- rightContent -->
</div> <!-- hgroup -->
<?php
}

/*
echo "<pre>";
print_r($wp_query->query_vars);
echo "</pre>";
 */
get_header(); ?>

<div class="main"><!-- safari appears to not support main-->
    <div class="searchBar searchHide" id="searchBar">
    <form class="search" action="<?php get_site_url(); ?>?" method="get">
        <input type="search" name="s" value="<?php echo get_query_var('s');?>" />
            <button type="submit">Search</button>
            <a href="#" class="searchOpen" onclick="toggleClassBySelector('#searchBar', 'searchHide')">Filter Results <i class="fa">&#xf0d7;</i></a>
            <a href="#" class="searchClose" onclick="toggleClassBySelector('#searchBar', 'searchHide')">Close <i class="fa">&#xf0d8;</i></a>
            <div class="searchAdvanced">
                <p><small>Caution, under construction. May not work.</small></p>
                <fieldset>
                    <legend>Result Type</legend>
                    <?php generatePostTypes();?>
                </fieldset>
                <fieldset>
                    <legend>Topics</legend>
                    <?php generateCategoryCheckboxes(); ?>
                </fieldset>
            </div><!--searchAdvanced-->
        </form>
    </div>
    <div id="searchRight" class="searchRight">
<?php 
if ( is_paged() ) {
    j3PageNav("", "", $standalone = true);
}
if ( have_posts() ) { 
    searchmap();
    while ( have_posts() ) { 
        the_post(); 
?>
<div class="hgroup hasPage">
    <div class="rightContent">
<?php
        if (get_post_type() == 'photo_album') {
            $format = 'gallery';
        } else {
            $format = get_post_format();
        }
get_template_part( 'excerpt', $format ); ?>
    </div> <!-- rightContent -->
</div> <!-- hgroup -->

<?php
    }
} else { 
    get_template_part( 'content', 'none' ); 
} 
j3PageNav("", "", $standalone = true); ?>
</div>
</div><!--main-->

<?php get_footer(); ?>
