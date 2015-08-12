<?php
/**
 * @package j3Custom
 */
if (!function_exists('j3ContentArticle') ) :


// Everything that goes in the article
function j3ContentArticle() {
    echo '<article class="visualPage">';

    j3PostThumbnail();
    echo '<h1 class="articleTitle"><a href="' . get_permalink() . '">';
    the_title();
    echo '</a></h1>';
    if (get_post_type() == 'post' ) {
        echo '<div class="date">' . get_the_author()
            . '. '. get_the_date('M j, Y') . '</div>';
    }
    the_content();
    j3PostNav();
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
?>
<div class="hgroup hasPage">
    <div class="rightContent">
        <?php j3ContentArticle(false); ?>
    </div> <!-- rightContent -->
    <aside>
        <div class="linkBlock">
            <?php if(function_exists('echo_ald_crp')) echo_ald_crp(); ?>
        </div><!--linkBlock-->
        <?php 
            j3ArticleCategories();
        ?>
    </aside>
<?php if (function_exists('synved_social_share_markup')) {
    echo '<aside>
        <div class="linkBlock">
        <h1>Share</h1>';
    echo synved_social_share_markup();
    echo '
        </div><!--linkBlock-->
        </aside>';
}
?>
    <aside class="mini">
        <a href="<?php the_permalink(); ?>">More details ...</a>
    </aside>
</div> <!-- hgroup -->
<?php comments_template(); ?>
