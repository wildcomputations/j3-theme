<?php
/**
 * @package j3_Destination
 * @version 1.0
 */
/*
Plugin Name: J3 Destinations
Plugin URI: https://github.com/wildcomputations/j3-theme
Description: Add destination hierachy to posts
Author: Emilie Phillips
Version: 1.0
Author URI: http://j3.org/

*/

/*****************************************************************
 * API Functions that themes can call                            *
 *****************************************************************/


/*****************************************************************
 * Internal plugin implementations                               *
 *****************************************************************/

/* This plugin makes a table to keep track of the categories available for each
* destination. */
$j3_dest_taxonomy = 'destination';

function j3_dest_activate()
{
    global $j3_dest_taxonomy;
    global $wpdb;
    $cat_table = $wpdb->base_prefix.'j3destactiv';
    $term_table = $wpdb->base_prefix.'terms';
    $sql = "CREATE TABLE $cat_table (
        dest_id bigint(20) UNSIGNED NOT NULL,
        cat_id bigint(20) UNSIGNED NOT NULL,
        PRIMARY KEY (dest_id, cat_id)
        )";
 /*
        FOREIGN KEY (dest_id) REFERENCES $term_table (term_id) ON DELETE CASCADE,
        FOREIGN KEY (cat_id) REFERENCES $term_table (term_id) ON DELETE CASCADE*/ 
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    // TODO populate the database table. Something isn't working here

    $tax_table = $wpdb->prefix . 'term_taxonomy';
    $relation_table = $wpdb->prefix . 'term_relationships';
    $terms_table = $wpdb->prefix . 'terms';
    $sql = "SELECT cat_tax.term_id as cat_id, dest_tax.term_id as dest_id, dest_tax.parent from $relation_table
JOIN $tax_table as dest_tax USING (term_taxonomy_id)
JOIN (SELECT * from $tax_table
      JOIN $relation_table USING (term_taxonomy_id)
      JOIN $terms_table using (term_id)
      WHERE taxonomy = 'category' and term_id != 1) as cat_tax USING (object_id)
WHERE dest_tax.taxonomy = 'destination'
GROUP BY cat_tax.term_id, dest_tax.term_id
";
    error_log($sql);
    $dest_cats = $wpdb->get_results( $sql);
    error_log(print_r($dest_cats, true));

    // $dest_cats has the categories for posts with that specific destination.
    // Now I need to propagate back up to the parent.
    $parent_by_child = array();
    foreach ( $dest_cats as $row) {
        $parent_by_child[$row->dest_id] = $row->parent;
    }
    error_log("Parent mapping"); // ok, I could have gotten this directly from the db
    error_log(print_r($parent_by_child, true));

    // now trickle up the categories
    $cats_by_dest = array();
    foreach ( $dest_cats as $row) {
        error_log("    new row");
        $dest_id = $row->dest_id;
        while ($dest_id != 0) {
            error_log("        dest " . $dest_id); 
            if (!array_key_exists($dest_id, $cats_by_dest)) {
                error_log(" not in array");
                $cats_by_dest[$dest_id] = array($row->cat_id);
            } else {
                error_log(" yes in array");
                $cats_by_dest[$dest_id][] = $row->cat_id;
            }
            $dest_id = $parent_by_child[$dest_id];
        }
    }
    error_log("complete cat list");
    error_log(print_r($cats_by_dest, true));
    // TODO: restart here
    // - get unique set of categories for each dest
    // - insert into table.
    // Make this a function which can also get called whenever a post is updated.

    add_option( "j3_destination_db_version", "1.0" );
}
register_activation_hook( __FILE__, "j3_dest_activate");

/** remove the db table entirely
 */
function j3_dest_uninstall()
{
    error_log("Uninstalling j3_dest");
    global $wpdb;
    $cat_table = $wpdb->base_prefix.'j3destactiv';
    $sql = "DROP TABLE IF EXISTS $cat_table;";
    $wpdb->query($sql);
    delete_option("j3_destination_db_version");

    // TODO also delete taxonomy and term data
    /* $terms = get_terms( $j3_dest_taxonomy, array( 'fields' => 'ids', 'hide_empty' => false ) );
          foreach ( $terms as $value ) {
               wp_delete_term( $value, $j3_dest_taxonomy );
          }
     */
}
register_uninstall_hook(__FILE__, 'j3_dest_uninstall');

function j3_dest_taxonomy_init()
{
    global $j3_dest_taxonomy;
    register_taxonomy(
        $j3_dest_taxonomy,
        array('post'),
        array(
            'label' => 'destinations',
            'description' => "Where a trip went",
            'hierarchical' => true,
            'show_admin_column' => true,
    ));
}
add_action( 'init', 'j3_dest_taxonomy_init');

