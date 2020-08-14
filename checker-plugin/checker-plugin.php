<?php
/**
 * @package j3_checker
 * @version 1.0
 */
/*
Plugin Name: J3 Post Checker
Plugin URI: https://github.com/wildcomputations/j3-theme
Description: Checks all the fields my website requires
Author: Emilie Phillips
Version: 1.0
Author URI: http://j3.org/

*/



/**** admin page for finding months with low number of entries ***/
// Query
// SELECT Year(meta_value) as year, Month(meta_value) as month, COUNT(*) as num_posts FROM `m11_postmeta` where meta_key = "j3tripdate" group by Year(meta_value), Month(meta_value) order by num_posts

// TODO move this function to be it's own php file. The file path can be used
// in place of a function name
// Future:
// * include months with zero posts
// * switch to a table
// * way to indicate that the month is complete and should be removed from the list
// * remove galleries from the count of posts
function j3_check_dates_page()
{
    global $wpdb;
    $query = "SELECT Year(meta_value) as year, Month(meta_value) as month, "
        . "COUNT(*) as num_posts "
        . "FROM $wpdb->postmeta "
        . "WHERE meta_key = 'j3tripdate' "
        . "GROUP BY Year(meta_value), Month(meta_value) "
        . "ORDER BY num_posts "
        . "LIMIT 15";
    $results = $wpdb->get_results($query);

    $output = "";
    if ( $results )
    {
        $output .= "<ul>";
        foreach ( (array) $results as $result )
        {
            $url = j3_date_get_month_link( $result->year, $result->month );
            $date_str = date('F Y', mktime(0, 0, 0, $result->month, 1, $result->year));
            $plural = '';
            if ( $result->num_posts > 1 ) $plural = 's';
            $output .= '<li><a href="' . $url . '">'
                . $date_str . ':</a> ' . $result->num_posts
                . ' post' . $plural . ' </li>';
        }
        $output .= "</ul>";
    }
    ?>
	<div class="wrap">
                <h2>Months with least posts</h2>
                <p>It looks like we went on very few trips these months. Please fix.</p>
<?php echo $output; ?>
	</div>
	<?php
}

function j3_check_print_ignore_location($post)
{
    // We'll use this nonce field later on when saving.
    $is_ignored = get_post_meta($post->ID, "j3check_ignoreloc", true);
?>
<input type="checkbox" name="j3_ignoreloc"
<?php checked( ! empty($is_ignored) );  ?> />
<?php
}

function j3checker_needs_position()
{
    global $wpdb;
    $querystr = "
        SELECT *
        FROM $wpdb->posts as posts
        LEFT JOIN (SELECT object_id, name as post_format_name
            FROM $wpdb->term_relationships as rel
            JOIN $wpdb->term_taxonomy using (term_taxonomy_id)
            JOIN $wpdb->terms using (term_id)
            where taxonomy = 'post_format') as post_format on (post_format.object_id = posts.ID)
        LEFT JOIN (SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta
                   where meta_key = '_latlngmarker'
                   ) as position on (position.post_id = posts.ID)
        LEFT JOIN (SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta
                   WHERE meta_key = 'j3check_ignoreloc'
                  ) AS ignore_tbl ON (ignore_tbl.post_id = posts.ID)
        WHERE post_type = 'post'
        AND post_status = 'publish'
        AND post_format.object_id is NULL
        AND position.post_id is NULL
        ORDER BY ignore_tbl.meta_value, posts.post_date DESC
        ";

    $pageposts = $wpdb->get_results($querystr, OBJECT);
    $add_date = function_exists("j3_date_post");
?>
<h2>Posts that need a location</h2>
<p>Please add a traveler's map location to these posts</p>
<table class="widefat striped posts">
  <thead>
    <tr>
      <th>Ignore</th>
      <th>Title</th>
      <th>Categories</th>
      <th>Date</th>
      <?php if ($add_date){ echo "<th>Trip Date</th>";} ?>
    </tr>
  </thead>
  <tbody>
<?php
/* TODO: learn to use WP_List_Table */
    if ($pageposts) {
        global $post;
        foreach ($pageposts as $post) {
            setup_postdata($post);
?>
    <tr>
      <td><?php j3_check_print_ignore_location($post); ?></td>
      <td><?php edit_post_link(the_title('', '', False)); ?></td>
      <td><?php echo get_the_category_list(", "); ?></td>
      <td><?php the_date(); ?></td>
<?php if ($add_date) { echo "<td>" . j3_date_post('Y-m-d') . "</td>";} ?>
    </tr>
<?php
        }
    }
?>
</tbody>
</table>
<?php
}


add_action ('admin_menu', function () {
    if (function_exists("j3_date_get_month_link")) {
        add_management_page('Months with few posts', 'Needs help: months', 'publish_posts',
            'j3_month_needs_help', 'j3_check_dates_page');
    }
    add_management_page('Posts with no location',
        'Needs help: location',
        'publish_posts',
        'j3_location_needs_help',
        'j3checker_needs_position');
});
