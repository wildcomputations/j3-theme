<?php
/**
 * @package j3_Galleries
 * @version 2.0
 */
/*
Plugin Name: Photo Galleries
Plugin URI: https://github.com/wildcomputations/j3-theme
Description: Split photo galleries out into their own post type.
Author: Emilie Phillips
Version: 1.0
Author URI: http://j3.org/
 */

// TODO: add quick menu widgets

// Remaining work for version 2.0
// - release notes saying added reference metadata
// - populate reference caches when plugin is activated.
// - remove reference caches when plugin is deactivated.
// - add a public API function for the theme to call to get all posts which
// refer to a gallery
// - test using this in the theme

/*****************************************************************
 * API Functions that themes can call                            *
 *****************************************************************/

/*****************************************************************
 * Internal plugin implementations                               *
 *****************************************************************/

function j3gallery_add_post_type()
{
    register_post_type("photo_album",
        array(
            'labels'      => array(
                'name'          => __('Photo Albums', 'textdomain'),
                'singular_name' => __('Photo Album', 'textdomain'),
            ),
            'description'  => "Photo album pages",
            'public'      => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'supports'    => array(
                'title', 'editor', 'comments', 'author', 'excerpt',
                'thumbnail', 'custom-fields', 'j3date'),
            'taxonomies'   => array( 'category', 'post_tag' )
        )
    );
}
add_action('init', 'j3gallery_add_post_type');

// Hook into post save to update or create the cache
add_action('save_post', 'j3gallery_reference_cache');

function _j3gallery_get_shortcode_ids( $post ) {
    // assumes the post is non-null
    if ( !has_shortcode($post->post_content, 'gallery') ) {
        return array();
    }

    // Get all galleries referenced from the current post
    $galleries = array();
    if ( preg_match_all( '/' . get_shortcode_regex(['gallery']) . '/s',
        $post->post_content, $matches, PREG_SET_ORDER ) ) {
        foreach ( $matches as $shortcode ) {
            if ( 'gallery' === $shortcode[2] ) {
                $shortcode_attrs = shortcode_parse_atts( $shortcode[3] );
                if ( ! is_array( $shortcode_attrs ) ) {
                    $shortcode_attrs = array();
                }

                if ( isset( $shortcode_attrs['id'] ) ) {
                    array_push($galleries, $shortcode_attrs['id']);
                }
            }

        }
    }
    
    return $galleries;
}

function j3gallery_reference_cache($post_id) {
    // Check if the post contains the [gallery] shortcode
    $post = get_post( $post_id );
    if ( !$post ) {
        return;
    }

    if (wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
        return;
    }

    $new_galleries = array_unique(_j3gallery_get_shortcode_ids($post));
    $old_galleries = get_post_meta($post_id, '_j3gallery_referrer_cache',
        false);

    // remove old ones
    foreach ( array_diff($old_galleries, $new_galleries) as $removed_gallery ) {
        delete_post_meta($post_id, '_j3gallery_referrer_cache', $removed_gallery);
        delete_post_meta($removed_gallery, '_j3gallery_reference_cache',
            $post_id);
    }

    // add new ones
    foreach ( array_diff($new_galleries, $old_galleries) as $added_gallery ) {
        add_post_meta($post_id, '_j3gallery_referrer_cache', $added_gallery);
        add_post_meta($added_gallery, '_j3gallery_reference_cache',
            $post_id);
    }
}
