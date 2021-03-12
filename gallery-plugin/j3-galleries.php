<?php
/**
 * @package j3_Galleries
 * @version 1.0
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
