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
    $trip_date = j3_date_post("F Y");
    if ( $trip_date ) {
        $year = j3_date_post("Y");
        $month = j3_date_post("m");
        $trip_date_html = '<li><a href="'.j3_date_get_year_link($year)
            . '/' . $month . '"/>Trips in ' . $trip_date
            . '</a></li>';
    } else {
        $trip_date_html = "";
    }
    return $trip_date_html;
}

endif; // function declarations
