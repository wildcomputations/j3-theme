<?php
/**
 * The archive by date
 *
 * @package j3Custom
 */

function spacer_day()
{
    echo '<div class="calendar space">';
    echo '</div>';
}

function one_day($day, $month, $year)
{
    $day_of_week = date("D", mktime(0, 0, 0, $month, $day, $year));
    $compr_date = sprintf("%04d-%02d-%02d", $year, $month, $day);
    echo '<div class="calendar '.$day_of_week.'">';
    echo '<p class="date">' . $day . '</p>';
    $galleries = array();
    $regulars = array();
    if (have_posts()) {
        while (j3_date_post("Y-m-d") == $compr_date) {
            if (get_post_format() == "gallery" ) {
                $display = '<a href="' . get_permalink() . '" class="photoLink">';
                $display .= get_the_post_thumbnail(null, 'thumbnail' );
                $display .= '  </a>';
                $galleries[] = $display;
            } else {
                $display = '<a href="' . get_permalink() . '">';
                $display .= get_the_title();
                $display .= '  </a><br>';
                $regulars[] = $display;
            }
            the_post();
        }
    }
    foreach ($galleries as $display) {
        echo $display;
    }
    foreach ($regulars as $display) {
        echo $display;
    }
    echo '</div>';
}

function spaces_needed($month, $year)
{
    $first_dow = date("w", mktime(0, 0, 0, $month, 1, $year));
    $sunday_norm = ($first_dow + 6) % 7;
    return $sunday_norm;
}

function one_month($month, $year)
{
    echo "<h1>".date("F", mktime(0, 0, 0, $month, 1, $year))."</h1>";
    $last_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $spaces = spaces_needed($month, $year);
    if ($spaces != 0) {
        foreach (range(1, $spaces) as $count) {
            spacer_day();
        }
    }
    foreach (range(1, $last_day) as $day) {
        one_day($day, $month, $year);
    }
}

get_header(); ?>

<div class="main"><!-- safari appears to not support main-->
<?php 
$year = j3_date_query_year();
$month = j3_date_query_month();
if (have_posts() ) {
    the_post();
}
if (empty($month)) {
    foreach (range(1, 12) as $month) {
        one_month($month, $year);
    }
} else {
    one_month($month, $year);
}
?>

</div><!--main-->

<?php get_footer(); ?>
