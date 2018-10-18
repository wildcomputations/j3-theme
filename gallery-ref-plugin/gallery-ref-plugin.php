<?php
/**
 * @package j3_Gallery-ref
 * @version 1.0
 */
/*
Plugin Name: Gallery references
Plugin URI: https://github.com/wildcomputations/j3-theme
Description: lookup table for posts which reference galleries
Author: Emilie Phillips
Version: 1.0
Author URI: http://j3.org/

*/

/*****************************************************************
 * API Functions that themes can call                            *
 *****************************************************************/

/** List of other posts which reference this gallery
 */
function j3_gallery_ref($post_id=Null)
{
    if (! isset($post_id)) {
        $post = get_post()->ID;
    }
}


/*****************************************************************
 * Internal plugin implementations                               *
 *****************************************************************/

/** Installation. Created db table and scans all existing posts.
 */
function j3_galref_install()
{
    error_log("Installing j3_gallery_ref");
    global $wpdb;
    $table_name = $wpdb->prefix . 'j3galref';
    $charset_collate = $wpdb->get_charset_collate();

    # XXX need to insert gallery multiple times
    $sql = "CREATE TABLE $table_name (
      gallery bigint(20) UNSIGNED NOT NULL,
      referrer bigint(20) UNSIGNED NOT NULL,
      PRIMARY KEY  (gallery)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    add_option( "j3_galref_db_version", "1.0" );
}
register_activation_hook( __FILE__, 'j3_galref_install' );

/** remove the db table entirely
 */
function j3_galref_uninstall()
{
    error_log("Uninstalling j3_gallery_ref");
    global $wpdb;
    $table_name = $wpdb->prefix . 'j3galref';
    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);
    delete_option("j3_galref_db_version");
}
register_uninstall_hook(__FILE__, 'j3_galref_uninstall');

/** Update references when users save posts
 */
function j3_galref_incr_update($post_id)
{
    $post = get_post($post_id);
    $pattern = get_shortcode_regex(['gallery']);
    if (preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )) {
        error_log("$post_id gallery " . gettype($matches[3]));
        if (empty($matches[3])) {
            return;
        }
        $all_attrs = join(' ', $matches[3]);
        error_log("attributes " . $all_attrs);
        if (empty($all_attrs)) {
            return;
        }
        $parsed_attrs = shortcode_parse_atts($all_attrs);
        if (array_key_exists('id', $parsed_attrs)) {
            error_log("should insert " . $parsed_attrs['id']);
        }
    }
}
add_action( 'save_post', 'j3_galref_incr_update');
