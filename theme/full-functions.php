<?php

if (!function_exists('j3AsideArticleLinks') ) :

function _j3CategoryList() {
    $categories = get_the_category();
    $result = "";
    $intro = "<b>Category:</b> ";
    $done_slugs = array();
    while ( $categories ) {
        $parents_todo = array();
        foreach ( $categories as $category ) {
            if ($category->term_id == 1) {
                continue;
            }
            $result .= '<li>' . $intro . '<a href="'
                . esc_url( get_category_link( $category->term_id ) )
                . '">' . $category->name
                .'</a></li>';
            array_push($done_slugs, $category->slug);
            $parents_todo += explode('|',
                get_category_parents($category->term_id, False, '|', True));
        }
        $categories = array();
        $parents_todo = array_unique($parents_todo);
        foreach ( array_diff($parents_todo, $done_slugs) as $slug) {
            if ( $slug ) {
                array_push($categories, get_category_by_slug($slug));
            }
        }
        print("<br>");
    }
    return $result;
}

function _j3DateTag() {
    if ( function_exists("j3_date_post") ) {
        $trip_date = j3_date_post("F Y");
    } else {
        $trip_date = NULL;
    }
    if ( $trip_date ) {
        $year = j3_date_post("Y");
        $month = j3_date_post("m");
        $trip_date_html = '<li><b>Date:</b> <a href="'.j3_date_get_month_link($year, $month)
            . '"/>' . $trip_date
            . '</a></li>';
    } else {
        $trip_date_html = "";
    }
    return $trip_date_html;
}

function j3AsideArticleLinks() {
    $catHtml = _j3CategoryList();
    $trip_date_html = _j3DateTag();
    $post_type = get_post_type();
    $post_type_html = '<li><b>Type:</b> <a href="'
        . get_post_type_archive_link($post_type)
        . '">' . get_post_type_object($post_type)->labels->singular_name
        . '</a></li>';

    echo '<div class="linkBlock">
              <h1>Read More</h1>
              <ul>';
    if ($trip_date_html) {
        echo $trip_date_html;
    }
    if ($catHtml) {
        echo $catHtml;
    }
    echo $post_type_html;
    echo '</ul></div> <!--linkBlock-->';
}

function j3AsideSkipToComments()
{
    $num_comments = get_comments_number();
    if (comments_open() || $num_comments > 0) {
        echo '<div class="linkBlock">';
        echo '<h1>Comments</h1>';
        echo '<p><a href="#commentsSection">';
        if ($num_comments > 0) {
            echo 'Skip to comments (' . get_comments_number() . ')';
        } else {
            echo 'Leave a comment';
        }
        echo '</a></p></div><!--linkBlock-->';
    }
}

function j3AsideMapLinks() {
    if (! is_plugin_active("travelers-map/travelers-map.php") ) return;
    if (! metadata_exists("post", get_the_ID(), '_latlngmarker') ) return;
    $long_name = get_post_type_object(get_post_type())->label;
    echo '<div class="linkBlock">';
    echo '<h1>Nearby ' . $long_name . '</h1>';
    echo do_shortcode(
        '[travelers-map height=220px init_maxzoom=13 centered_on_this=true '
        . 'max_cluster_radius=1 '
        . 'post_types=' . get_post_type()
        . ']');
    echo '<br><p><a href="' . get_site_url(null, "?s=") . '">All markers</a></p>';
    echo '</div>';
}

function j3AsideCtaWidgets()
{
    if ( is_active_sidebar('cta_box') ) {
        echo '<div class="linkBlock">';
        dynamic_sidebar( 'cta_box' );
        echo '</div> <!-- linkBlock -->';
    }
}


function j3ArticleHead($include_author, $subhead='') {
    echo '<div class="articleHead"><h1><a href="' . get_permalink() . '">';
    the_title();
    echo '</a></h1>';
    if (!empty($subhead)) {
        echo "<h2>" . $subhead . "</h2>";
    }
    if (function_exists("j3_date_post") ) {
        $trip_date = j3_date_post('F j, Y');
    } else {
        $trip_date = NULL;
    }
    if (!empty($trip_date) || $include_author) {
        if (!empty($trip_date)) {
            echo '<div class="date">';
            echo $trip_date;
            echo '</div>';
        }
        if ($include_author) {
            echo '<div class="signature">';
            echo get_the_author();
            echo '</div>';
        }
    }
    echo "</div>";
}

function j3ArticleFooter() {
    echo '<div class="articleFooter">Written ' . get_the_date();
    $u_time = get_the_time('U');
    $u_modified_time = get_the_modified_time('U');
    if ($u_modified_time >= $u_time + 86400) {
        echo ", updated ";
        the_modified_date();
    }
    echo '</div>';
}

function j3ContentComments()
{
    if (comments_open()) {
        echo '<article class="subPage" id="commentsSection">';
        comments_template();
        echo '</article>';
    }
}


endif; // function declarations
