<?php
/**
 * @package j3Custom
 */
require 'full-functions.php';

if (!function_exists('j3ContentArticle') ) :


// Everything that goes in the article
function j3ContentArticle() {
    echo '<article class="visualPage">';

    j3PostThumbnail();
    echo '<h1 class="articleTitle"><a href="' . get_permalink() . '">';
    the_title();
    echo '</a></h1>';
    if (get_post_type() == 'post' ) {
        echo '<div class="date">';
        $trip_date = j3_date_post('F j, Y');
        if (!empty($trip_date)) {
            echo '<b>' . $trip_date . '</b><br>';
        }
        echo '<b>' . get_the_author() . '</b> updated ';
        the_modified_date();
        echo '</div>';
    }
    the_content();
    echo '</article>';

    if (comments_open()) {
        echo '<article class="subPage">';
        comments_template();
        echo '</article>';
    }
}

function j3ArticleLinks() {
    $catHtml = j3AsideCategories();
    $trip_date_html = j3AsideCalendar();
    if ( $catHtml || $trip_date_html ) {
        echo '<div class="linkBlock">
                  <h1>Read More</h1>
                  <ul>';
        if ($trip_date_html) {
            echo $trip_date_html;
        }
        if ($catHtml) {
            echo $catHtml;
        }
        echo '</ul></div> <!--linkBlock-->';
    }
}

function j3CtaWidgets()
{
    if ( is_active_sidebar('cta_box') ) {
        echo '<div class="linkBlock">';
        dynamic_sidebar( 'cta_box' );
        echo '</div> <!-- linkBlock -->';
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
        <?php j3ContentArticle(); ?>
    </div> <!-- rightContent -->
<?php
    if (get_post_type() == 'post' ) {
?>
    <aside>
        <?php 
            j3ArticleLinks();
        ?>
        <div class="linkBlock">
            <?php if(function_exists('echo_crp')) echo_crp(); ?>
        </div><!--linkBlock-->
<?php
            j3CtaWidgets()
?>
    </aside>
<?php 
    } 
?>
    <aside class="mini">
        <a href="<?php the_permalink(); ?>">More details ...</a>
    </aside>
</div> <!-- hgroup -->
<?php 
?>
