<?php

if (!function_exists('j3AsideCategories') ) :

function j3AsideCategories() {
    $categories = get_the_category();
    $result = "";
    $intro = "Latest";
    foreach ( $categories as $category ) {
        if ($category->term_id == 1) {
            continue;
        }
        $result .= '<li><a href="'
            . esc_url( get_category_link( $category->term_id ) )
            . '">' . $intro . ' ' . $category->name
            .' reports</a></li>';
        $intro = "Or the latest";
    }
    return $result;
}

function j3AsideCalendar() {
    if ( function_exists("j3_date_post") ) {
        $trip_date = j3_date_post("F Y");
    } else {
        $trip_date = NULL;
    }
    if ( $trip_date ) {
        $year = j3_date_post("Y");
        $month = j3_date_post("m");
        $trip_date_html = '<li><a href="'.j3_date_get_month_link($year, $month)
            . '"/>Trips in ' . $trip_date
            . '</a></li>';
    } else {
        $trip_date_html = "";
    }
    return $trip_date_html;
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
        echo '<div class="date">';
        if (!empty($trip_date)) {
            echo '<b>' . $trip_date . '</b><br>';
        }
        if ($include_author) {
            echo get_the_author();
        }
        echo '</div>';
    }
    echo "</div>";
}

function j3ArticleFooter() {
    echo '<div class="articleFooter"><div class="date">Written ' . get_the_date();
    $u_time = get_the_time('U');
    $u_modified_time = get_the_modified_time('U');
    if ($u_modified_time >= $u_time + 86400) {
        echo ", updated ";
        the_modified_date();
    }
    echo '</div></div>';
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
