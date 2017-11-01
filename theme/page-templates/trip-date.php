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
    $first_gallery = NULL;
    $first_post = NULL;
    while (have_posts() && j3_date_post("Y-m-d") == $compr_date) {
        if (get_post_format() == "gallery" ) {
            $display = '<a href="' . get_permalink() . '" class="photoLink">';
            $display .= get_the_post_thumbnail(null, 'thumbnail' );
            $display .= '  </a>';
            $galleries[] = $display;
            if (empty($first_gallery)) {
                $first_gallery = '<a href="' . get_permalink() . '">';
                $first_gallery .= get_the_title();
                $first_gallery .= '</a>';
            }
        } else {
            $display = '<a href="' . get_permalink() . '"><h1>';
            $display .= get_the_title();
            $display .= '</h1></a>';
            $regulars[] = $display;
            if (empty($first_post) &&
                has_post_thumbnail()) {
                $first_post = get_the_post_thumbnail(null, 'thumbnail' );
            }
        }
        the_post();
    }
    if (empty($galleries)) {
        echo $first_post;
    }
    foreach ($galleries as $display) {
        echo $display;
    }
    if (empty($regulars)) {
        echo $first_gallery;
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
    echo '<div class="visualPage"><div class="calendarInsert">';
    echo '<h1 class="calendarTitle">'.date("F Y", mktime(0, 0, 0, $month, 1, $year))."</h1>";
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
    echo '</div></div>';
}

/**
 * Get First Post Date Function
 *
 * @param  $format Type of date format to return, using PHP date standard, default Y-m-d
 * @return Date of first post
 */
function ax_first_post_date($format = "Y-m-d") {
    // Setup get_posts arguments
    $ax_args = array(
        'numberposts' => 1,
        'post_status' => 'publish',
        'order' => 'ASC'
    );

    // Get all posts in order of first to last
    $ax_get_all = get_posts($ax_args);

    // Extract first post from array
    $ax_first_post = $ax_get_all[0];
    if (empty($ax_first_post)) {
        return date($format);
    }

    // Assign first post date to var
    $ax_first_post_date = $ax_first_post->post_date;

    // return date in required format
    return date($format, strtotime($ax_first_post_date));
}

function month_selector($year, $month=NULL)
{
    echo '<select name="tripmonth">
    <option value="">--</option>';
    for ($monthnum = 1; $monthnum <= 12; $monthnum++) {
        $monthstring = sprintf("%02d", $monthnum);
        echo '<option value="'.$monthstring.'"';
        if ($monthnum == $month) {
            echo ' selected';
        }
        echo '>'.date("F", mktime(0, 0, 0, $monthnum, 1, $year));
        echo '</option>';
    }
    echo "</select>";
}

function year_selector($year)
{
    echo '<select name="tripyear"';
    $now_year = date("Y");
    for ($yearnum = ax_first_post_date("Y");
        $yearnum <= $now_year; $yearnum++) {
        echo '<option value="'.$yearnum.'"';
        if ($yearnum == $year) {
            echo ' selected';
        }
        echo '>'.$yearnum.'</option>';
    }
    echo '</select>';
}

function prev_link($year, $month)
{
    echo '<div class="navPrevious">';
    if (empty($month)) {
        echo '<a href="'.site_url('/trip-date/'.($year - 1).'/');
        echo '">Previous Year</a>';
    } else {
        $prev_month = $month - 1;
        if ($prev_month == 0) {
            $prev_month = 12;
            $prev_year = $year - 1;
        } else {
            $prev_year = $year;
        }
        $prev_month = sprintf("%02d", $prev_month);
        echo '<a href="'.site_url(
            '/trip-date/'.$prev_year.'/'.$prev_month.'/');
        echo '">Previous Month</a>';
    }
    echo '</div><!--navPrevious-->';
}

function next_link($year, $month)
{
    echo '<div class="navNext">';
    if (empty($month)) {
        echo '<a href="'.site_url('/trip-date/'.($year + 1).'/');
        echo '">Next Year</a>';
    } else {
        $next_month = $month + 1;
        if ($next_month == 13) {
            $next_month = 1;
            $next_year = $year + 1;
        } else {
            $next_year = $year;
        }
        $next_month = sprintf("%02d", $next_month);
        echo '<a href="'.site_url(
            '/trip-date/'.$next_year.'/'.$next_month.'/');
        echo '">Next Month</a>';
    }
    echo '</div><!--navNext-->';
}

get_header(); 
$year = j3_date_query_year();
$month = j3_date_query_month();
?>

<div class="main"><!-- safari appears to not support main-->
    <div class="calendarNav">
        <?php prev_link($year, $month); ?>
            <form action="<?php echo site_url('/trip-date/'); ?>">
            <?php month_selector($year, $month); ?>
            <?php year_selector($year); ?>
            <input type="submit" value="Submit">
        </form>
        <?php next_link($year, $month); ?>
    </div>

<?php
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
