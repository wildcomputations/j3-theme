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
    $image = NULL;
    $first_gallery_image = NULL;
    $first_post_image = NULL;
    while (!empty(get_post()) && j3_date_post("Y-m-d") == $compr_date) {
        if (get_post_format() == "gallery" ) {
            $display = 'Album: <a href="' . get_permalink() . '">';
            $display .= get_the_title();
            $display .= '</a><br>';
            $galleries[] = $display;
            if (empty($first_gallery_image)) {
                $first_gallery_image = get_the_post_thumbnail(
                    null, 'thumbnail' );
            }
        } else {
            $display = '<h1><a href="' . get_permalink() . '">';
            $display .= get_the_title();
            $display .= '</a></h1>';
            $regulars[] = $display;
            if (empty($first_post_image) &&
                has_post_thumbnail()) {
                $first_post_image = get_the_post_thumbnail(null, 'thumbnail' );
            }
        }
        if (have_posts()) {
            the_post();
        } else {
            break;
        }
    }
    if (!empty($first_gallery_image)) {
        echo $first_gallery_image;
    } elseif (!empty($first_post_image)) {
        echo $first_post_image;
    }

    foreach ($regulars as $display) {
        echo $display;
    }
    foreach ($galleries as $display) {
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

function one_month_expanded($month, $year)
{
    echo '<div class="visualPage"><div class="calendarInsert">';
    echo '<h1 class="calendarTitle">'.date("F Y", mktime(0, 0, 0, $month, 1, $year))."</h1>";
    echo '<p class="calendarHeading">Monday</p>
        <p class="calendarHeading">Tuesday</p>
        <p class="calendarHeading">Wednesday</p>
        <p class="calendarHeading">Thursday</p>
        <p class="calendarHeading">Friday</p>
        <p class="calendarHeading">Saturday</p>
        <p class="calendarHeading">Sunday</p>';
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
        'order' => 'ASC',
        'meta_key' => 'j3tripdate',
        'orderby' => 'meta_value',
    );

    // Get all posts in order of first to last
    $ax_get_all = get_posts($ax_args);

    // Extract first post from array
    $ax_first_post = $ax_get_all[0];
    if (empty($ax_first_post)) {
        return date($format);
    }

    // return date in required format
    $trip_date = j3_date_post($format, $ax_first_post);

    if (empty($trip_date)) {
        return date($format);
    }
    return $trip_date;
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
    $now_year = date("Y");
    echo '<select name="tripyear">';
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
        if ($year == date("Y")) {
            echo '<div style="width:8em;"></div>';
        } else {
            echo '<a href="'.site_url('/trip-date/'.($year + 1).'/');
            echo '">Next Year</a>';
        }
    } else {
        if ($month == date('m')
            && $year == date('Y')) {
            echo '<div style="width:9em;"></div>';
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
    }
    echo '</div><!--navNext-->';
}

get_header(); 
$year = j3_date_query_year();
$month = j3_date_query_month();
?>
<script>
function show_expand(linkObj)
{
    var main = jQuery(".main");
    main.removeClass("activateCompact");
    main.addClass("activateExpand");
}

function show_compact(linkObj)
{
    var main = jQuery(".main");
    main.removeClass("activateExpand");
    main.addClass("activateCompact");
}
</script>

<div class="main activateExpand">
    <div class="calendarNav">
        <div class="paramsToggle"></div>
        <a class="paramsToggle" onclick='show_expand(this)'>Expanded View</a>
        <a class="paramsToggle" onclick='show_compact(this)'>Compact View</a>
        <div class="paramsToggle"></div>
    </div>
    <div class="calendarNav">
        <?php prev_link($year, $month); ?>
            <form action="<?php echo site_url('/trip-date/'); ?>">
            <?php month_selector($year, $month); ?>
            <?php year_selector($year); ?>
            <input type="submit" value="Submit">
        </form>
        <?php next_link($year, $month); ?>
    </div>

<div class="expanded">
<?php
if (have_posts() ) {
    the_post();
}
if (empty($month)) {
    foreach (range(1, 12) as $month) {
        one_month_expanded($month, $year);
    }
} else {
    one_month_expanded($month, $year);
}
echo '</div>';

rewind_posts();
if ( have_posts() ) {
    echo '<div class="hgroup hasPage compact">';
    $prev_year = Null;
    $prev_month = Null;
    while ( have_posts() ) {
        the_post(); 
        $new_year = j3_date_post('Y');
        $new_month = j3_date_post('F');
        if (empty($prev_year)) {
            echo '<h1 class="topicTitle">' . $new_year . '</h1>';
            echo '<div class="rightContent visualPage history hasStack">';
        }
        if ($new_month != $prev_month) {
            if (!empty($prev_month)) {
                echo '</div> <!-- month -->';
            }
            echo "<h1>" . $new_month . "</h1>";
            echo '<div class="month">';
        }
        $prev_year = $new_year;
        $prev_month = $new_month;
        if (get_post_type() == 'photo_album') {
            $format = 'gallery';
        } else {
            $format = get_post_format();
        }
        get_template_part( 'card', $format ); 
    } ?>
    </div> <!-- month -->
</div> <!-- rightContent -->
</div> <!-- hgroup -->
<?php } ?>


</div><!--main-->

<?php get_footer(); ?>
