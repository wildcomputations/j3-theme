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
        echo '<div class="date">';
        $trip_date = j3_date_post('M j, Y');
        if (!empty($trip_date)) {
            echo $trip_date . '<br>';
        }
        echo 'Author ' . get_the_author();
        echo '</div>';
    }
    the_content();
    if (get_post_type() == 'post' ) {
        echo '<p class="date alignright">Posted ' . get_the_date('M j, Y') . '</p>';
    }
    echo '</article>';
}

function j3ArticleCategories() {
    $catHtml = get_the_category_list(
        ' trips.</li><li>Or the latest ');
    $trip_date = j3_date_post("F Y");
    if ( $trip_date ) {
        $year = j3_date_post("Y");
        $month = j3_date_post("m");
        $trip_date_html = '<li><a href="'.j3_date_get_year_link($year)
            . '/' . $month . '"/>Trips in ' . $trip_date
            . '</a></li>';
    }
    if ( $catHtml || $trip_date ) {
        echo '<div class="linkBlock cta">
                  <h1>Read More</h1>
                  <ul>';
        if ($trip_date) {
            echo $trip_date_html;
        }
        if ($catHtml) {
            echo '<li>Latest ' . $catHtml . ' trips.</li>';
        }
        echo '</ul></div> <!--linkBlock-->';
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
            j3ArticleCategories();
        ?>
        <div class="linkBlock">
            <?php if(function_exists('echo_crp')) echo_crp(); ?>
        </div><!--linkBlock-->
    </aside>
<?php 
    } 
?>
    <aside class="mini">
        <a href="<?php the_permalink(); ?>">More details ...</a>
    </aside>
</div> <!-- hgroup -->
<?php 
if (get_post_type() == 'post' ) {
    comments_template();
}
?>
