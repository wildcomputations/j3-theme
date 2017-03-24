<?php
/**
 * @package j3Custom
 */
if (!function_exists('j3ContentMeta') ) :

function j3ContentMeta() {
    if ( !is_single() || current_user_can( 'edit_post', get_the_ID() ) ){
        echo '<div class="linkBlock">
            <ul>
            <li>';
        if ( comments_open() && !is_single() ) {
            comments_popup_link( 'Leave a comment', '1 comment', '% comments');
        }
        edit_post_link('Edit', '<li>');
        echo '</ul>
            </div><!--linkBlock-->';
    }
}

// Everything that goes in the article
function j3ContentExcerpt() {
    echo '<article class="visualPage postExcerpt ">';

    j3PostThumbnail('medium', true);
    echo '<h1 class="articleTitle"><a href="' . get_permalink() . '">';
    the_title();
    echo '</a></h1>';
    if (get_post_type() == 'post' ) {
        echo '<div class="date">' . get_the_author()
            . '. '. get_the_date('M j, Y') . '</div>';
    }
    the_excerpt();
    echo '<p><a class="moretag" href="'. get_permalink() . '"> Read Full Post</a></p>';
    echo '</article>';
}

function j3ArticleCategories() {
    $catHtml = get_the_category_list();
    if ( $catHtml ) {
        echo '<div class="linkBlock">
                  <h1>Topic</h1>';
        echo $catHtml;
        echo '</div> <!--linkBlock-->';
    }
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

if (is_front_page()) {
    j3ContentExcerpt();

} else { ?>

<div class="hgroup hasPage">
    <div class="rightContent">
        <?php j3ContentExcerpt(); ?>
    </div> <!-- rightContent -->
    <aside>
        <?php 
            j3ArticleCategories();
        ?>
    </aside>
</div> <!-- hgroup -->


<?php
} ?>
