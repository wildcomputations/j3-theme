<?php
/* Mockups of wordpress functions for unit testing
 */

if (! function_exists("bloginfo")) :
function bloginfo( $var) {
    if ($var == 'name') {
        echo "The Site Title";
    }

    if ($var == 'description') {
        echo "witty tagline";
    }
}
endif;

function wp_title($sep, $display, $seplocation) {
    echo "The page title";
}

function wp_head() {
}
function body_class( $class = "") {
    return "";
}
function home_url() {
    return "/";
}

if (! function_exists("get_search_form")):
function get_search_form( $echo = true )
{
    $result = '<form class="searchForm" action="http://j3.org/" method="get" role="search">
                <label>
                    <input class="search-field" type="search" name="s" value="" placeholder="Search ..."/>
                </label>
                </form>';
    if ($echo) echo $result;
    return $result;
}
endif;

?>
