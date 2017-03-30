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

/** Generates a user selection for date.
 * The input fields are 
 * $id_prefix . 'day'
 * $id_prefix . 'month'
 * $id_prefix . 'year'
 */
function j3_time_chooser( $has_date, $date, $id_prefix ) {
    global $wp_locale;

    $time_adj = current_time('timestamp');
    $day = ($has_date) ? mysql2date( 'd', $date, false ) : gmdate( 'd', $time_adj );
    $month = ($has_date) ? mysql2date( 'm', $date, false ) : gmdate( 'm', $time_adj );
    $year = ($has_date) ? mysql2date( 'Y', $date, false ) : gmdate( 'Y', $time_adj );

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
                    $_POST['trip_date_year'] . '-'
                    . sprintf('%02d', $_POST['trip_date_month'])
                    . '-'
                    . sprintf('%02d', $_POST['trip_date_day']));
            } 
        } else {
            delete_post_meta( $post_id, 'j3tripdate');
        }
    }
}
add_action( 'save_post', 'j3DateMetaBoxSave');

function j3_date_get_year_link( $year )
{
    if ( !$year )
    {
        $year = gmdate('Y', current_time('timestamp'));
    }
    return home_url( '/trip-date/' . $year );
}

function j3_date_get_archives($args = '')
{
    global $wpdb;
    $defaults = array(
        'format' => 'html', 'before' => '',
        'after' => '',
        'order' => 'DESC',
        'echo' => 1,
    );

    $r = wp_parse_args( $args, $defaults );

    $order = strtoupper( $r['order'] );
    if ( $order !== 'ASC' ) {
        $order = 'DESC';
    }

    $query = "SELECT DISTINCT YEAR(meta_value) AS year "
        . "FROM " . $wpdb->prefix . "postmeta "
        . "WHERE meta_key = 'j3tripdate' "
        . "ORDER BY year " . $order;

    $results = $wpdb->get_results($query);

    $output = "";
    if ( $results )
    {
        foreach ( (array) $results as $result )
        {
            $url = j3_date_get_year_link( $result->year );
            $text = sprintf( '%d', $result->year );
            $output .= get_archives_link( $url, $text,
                $r['format'], $r['before'], $r['after'] );
        }
    }
    if ( $r['echo'] ) {
        echo $output;
    } else {
        return $output;
    }
}

function j3_date_register_query_vars( $vars )
{
    $vars[] = 'tripyear';
    return $vars;
}
add_filter( 'query_vars', 'j3_date_register_query_vars');

function j3_date_pre_get_posts( $query )
{
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    //error_log("Pre get post" . print_r($query->query_vars, True));

    $tripyear = get_query_var( 'tripyear' );
    if ( !empty($tripyear) && is_numeric($tripyear))
    {
        $meta_query = array( 'key' => 'j3tripdate',
            'value' => array($tripyear . '-01-01', $tripyear . '-12-31'),
            'compare' => 'BETWEEN',
            'type' => 'DATE');
        $query->set('meta_query', $meta_query);
        $query->set('meta_key', 'j3tripdate');
        $query->set('orderby', 'meta_value');
        $query->set('order', 'DESC');
    }
}
add_action( 'pre_get_posts', 'j3_date_pre_get_posts');

// Display the trip date in the summary admin page
function j3_date_add_admin_column($columns)
{
    return array_merge( $columns,
        array('j3tripdate' => __('Trip Date')) );
}
add_filter('manage_posts_columns', 'j3_date_add_admin_column');

function j3_date_populate_columns($column, $post_id)
{
    if ($column != 'j3tripdate')
    {
        return;
    }
    echo get_post_meta($post_id, "j3tripdate", true);
}
add_action( 'manage_posts_custom_column', 'j3_date_populate_columns', 10, 2);

function j3_date_add_rewrite_rules()
{
    add_rewrite_rule('^trip-date/([0-9]+)/?$', 'index.php?tripyear=$matches[1]',
    'top');
}
add_action('init', 'j3_date_add_rewrite_rules', 10, 0);

function j3_date_is_archive( )
{
    $tripyear = get_query_var( 'tripyear' );
    return !empty($tripyear) && is_numeric($tripyear);
}

function j3_date_post( $format, $post=NULL )
{
    if (empty($post)) {
        $post = get_post();
    }
    $current_trip_date = get_post_meta($post->ID, "j3tripdate", true);

    if (empty($current_trip_date)) {
        return;
    } else {
        return mysql2date( $format, $current_trip_date, false);
    }
}

function j3_date_archive_template( $template )
{
    $new_template = locate_template(
        array( 'trip-date.php', 'date.php' ) );
    if ('' != $new_template) return $new_template;
    return $template;
}

function j3_date_template_hierarchy()
{
    if (j3_date_is_archive())
    {
        add_filter( 'template_include', 'j3_date_archive_template');
    }
}
add_action( 'template_redirect', 'j3_date_template_hierarchy');
