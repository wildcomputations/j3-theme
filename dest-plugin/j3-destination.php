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
    $terms = get_terms( array('taxonomy' => 'category') );
    error_log("categories");
    error_log(print_r($terms, true));

    $term_query = new WP_Term_Query( array ('taxonomy' => 'destination') );
    error_log("destinations");
    error_log(print_r($term_query, true));

    $sql = "SELECT COUNT(object_id) as num_posts, cat_tax.term_id as cat_id, cat_tax.slug from m11_term_relationships
JOIN m11_term_taxonomy as dest_tax USING (term_taxonomy_id)
JOIN (SELECT * from m11_term_taxonomy 
      JOIN m11_term_relationships USING (term_taxonomy_id)
      JOIN m11_terms using (term_id)
      WHERE taxonomy = 'category') as cat_tax USING (object_id)
WHERE dest_tax.taxonomy = 'destination' and dest_tax.term_id = 27
GROUP BY cat_tax.term_id";

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

