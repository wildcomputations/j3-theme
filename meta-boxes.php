<?php 

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
    wp_nonce_field( 'j3MetaBoxDate', 'j3_save_trip_date' );
?>
<div class="tripdate">
<?php j3_time_chooser( 0,  "", "trip_date"); ?>
</div>
<?php
}

function j3PostHideHtml($post)
{
    $currentlyHidden = get_post_meta($post->ID, "hidepost", true);
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'j3MetaBoxHide', 'meta_box_nonce' );
?>
<input type="checkbox" name="hidepost"
<?php checked( $currentlyHidden, 'hide' ); ?> />
<label for="hidepost">Only show in archives</label>
<?php
}

