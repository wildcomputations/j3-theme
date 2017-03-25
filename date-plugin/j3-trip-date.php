<?php
/**
 * @package j3_Trip-Date
 * @version 1.0
 */
/*
Plugin Name: Trip Date
Plugin URI: https://github.com/wildcomputations/j3-theme
Description: Add a trip date to posts and functions to load posts by trip date.
Author: Emilie Phillips
Version: 1.0
Author URI: http://j3.org/
*/

function post_has_trip_date() {
    $post = get_post();
    $edit = ! ( in_array($post->post_status, array('draft', 'pending') ) && (!$post->post_date_gmt || '0000-00-00 00:00:00' == $post->post_date_gmt ) );
}

/** Generates a user selection for date.
 * The input fields are 
 * $id_prefix . 'day'
 * $id_prefix . 'month'
 * $id_prefix . 'year'
 */
function j3_time_chooser( $has_date, $date, $id_prefix ) {
    global $wp_locale;

    $time_adj = current_time('timestamp');
    $day = ($has_date) ? substr($date, 6, 2) : gmdate( 'd', $time_adj );
    $month = ($has_date) ? substr($date, 4, 2) : gmdate( 'm', $time_adj );
    $year = ($has_date) ? substr($date, 0, 4) : gmdate( 'Y', $time_adj );

    $month_html = '<label>
        <span class="screen-reader-text">' . __( 'Month' ) . '</span>
        <select id="' . $id_prefix . '_month" name="' . $id_prefix . '_month">\n';
    for ( $i = 1; $i < 13; $i++ ) {
            $monthnum = zeroise($i, 2);
            $monthtext = $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) );
            $month_html .= "\t\t\t" . '<option value="' . $monthnum . '" data-text="' . $monthtext . '" ' . selected( $monthnum, $month, false ) . '>';
            /* translators: 1: month number (01, 02, etc.), 2: month abbreviation */
            $month_html .= sprintf( __( '%1$s-%2$s' ), $monthnum, $monthtext ) . "</option>\n";
    }
    $month_html .= '</select></label>';

    $day_id = $id_prefix . "_day";
    $day_html = '<label><span class="screen-reader-text">' . __( 'Day' ) . '</span>
        <input type="text" id="' . $day_id . '" name="' . $day_id . '"
         value="' . $day . '" size="2" maxlength="2" autocomplete="off" />
        </label>';
    $year_id = $id_prefix . '_year';
    $year_html = '<label><span class="screen-reader-text">' . __( 'Year' ) . '</span>
        <input type="text" id="' . $year_id . '" name="' . $year_id . '"
         value="' . $year . '" size="4" maxlength="4" autocomplete="off" />
        </label>';

    echo '<div class="timestamp-wrap">';
    /* translators: 1: month, 2: day, 3: year, 4: hour, 5: minute */
    printf( __( '%1$s %2$s, %3$s' ), $month_html, $day_html,
        $year_html);

    echo "</div>";
}


function j3PostDateHtml($post)
{
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'save_trip_date'.$post->ID, 'j3-date' );
    $current_trip_date = get_post_meta($post->ID, "j3tripdate", true);
    echo "Curent trip date ";
    print_r($current_trip_date);
?>
<div class="tripdate">
<input type="checkbox" name="trip_date_valid"
<?php checked( ! empty($current_trip_date));  ?> />
Set trip date<br>
<?php j3_time_chooser( ! empty($current_trip_date),  $current_trip_date,
                      "trip_date"); ?>
</div>
<?php
}

function j3DateMetaBoxes ()
{
    add_meta_box("j3tripdatediv", "Trip Date", 'j3PostDateHtml',
        'post', 'side');
}
add_action( 'add_meta_boxes', 'j3DateMetaBoxes');

function j3ValidYear($key)
{
    // Y2.1k bug
    return ( isset( $_POST[$key] )
            && is_numeric( $_POST[$key] )
            && $_POST[$key] > 1900
            && $_POST[$key] <= 2100);
}

function j3ValidMonth($key)
{
    return ( isset( $_POST[$key] )
            && is_numeric( $_POST[$key] )
            && $_POST[$key] > 0
            && $_POST[$key] <= 12);
}

function j3ValidDay($key, $month, $year)
{
    if ( ! isset( $_POST[$key] )
        || ! is_numeric( $_POST[$key] )
        || $_POST[$key] <= 0)
    {
            return FALSE;
    }

    return $_POST[$key] <= cal_days_in_month(CAL_GREGORIAN,$month,$year);
}

function j3DateMetaBoxSave($post_id)
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post', $post_id ) ) return;

    // if our nonce is there, we trust the hidden request
    if( check_admin_referer( 'save_trip_date'.$post_id, 'j3-date' ) ) {
        if (isset( $_POST['trip_date_valid'] ))
        {
            if (j3ValidYear( 'trip_date_year' )
                && j3ValidMonth( 'trip_date_month' )
                && j3ValidDay( 'trip_date_day',  $_POST['trip_date_month'],
                    $_POST['trip_date_year']) )
            {
                update_post_meta( $post_id, 'j3tripdate',
                    $_POST['trip_date_year']
                    . sprintf('%02d', $_POST['trip_date_month'])
                    . sprintf('%02d', $_POST['trip_date_day']));
            } 
        } else {
            delete_post_meta( $post_id, 'j3tripdate');
        }
    }
}
add_action( 'save_post', 'j3DateMetaBoxSave');
