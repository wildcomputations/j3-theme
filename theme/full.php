<?php
/**
 * @package j3Custom
 */
require 'full-functions.php';

if (!function_exists('j3ContentArticle') ) :

// bread crumb navigation
function j3BreadCrumbs() {
    // Use a callback registration to let different post types and plugins have their own breadcrumbs
    // TODO attachment pages should link to their parent
    // TODO put photo album parent links here too
    global $post;

    if (get_post_type() != 'page') {
        return;
    }

    $ancestors = get_post_ancestors($post->ID);
    if (count($ancestors) == 0) return;
    $crumbs = "";
    foreach ($ancestors as $ancestor_id) {
        $crumbs = '<a href="' . get_permalink($ancestor_id)
            . '">' . get_the_title($ancestor_id)
            . '</a> > ' . $crumbs;
    }
    $crumbs .= get_the_title();
?>
<div class="hgroup">
    <p class="breadcrumbs">
        <?php echo $crumbs; ?>
    </p>
</div>
<?php
}


// Everything that goes in the article
function j3ContentArticle() {
    echo '<article class="visualPage">';

    j3PostThumbnail();
    $is_post = get_post_type() == 'post';
    j3ArticleHead($is_post);
    the_content();
    if (get_post_type() == 'post' ) {
        j3ArticleFooter();
    }
    echo '</article>';
}

endif; // function declarations

if ( post_password_required() ) {
    echo '<div class="hgroup hasPage">
            <div class="rightContent">
                <p>This post requires authentication</p>
            </div>
        </div>';
	return;
}
?>
<?php j3BreadCrumbs(); ?>
<div class="hgroup hasPage">
     <div class="rightContent">
        <?php j3ContentArticle(); ?>
    </div> <!-- rightContent -->
<?php
    if (get_post_type() == 'post' ) {
?>
    <aside class=leftBar>
        <?php 
            j3AsideSkipToComments();
            j3AsideArticleLinks();
            j3AsideMapLinks();
            if(function_exists('echo_crp')) {
echo '<div class="linkBlock">';
echo_crp();
echo '</div><!--linkBlock-->';
            }
            j3AsideCtaWidgets()
?>
    </aside>
<?php 
    } 
?>
    <aside class="mini">
        <a href="<?php the_permalink(); ?>">More details ...</a>
    </aside>
    <div class="rightContent">
<?php j3ContentComments(); ?>
    </div> <!-- rightContent -->
</div> <!-- hgroup -->
