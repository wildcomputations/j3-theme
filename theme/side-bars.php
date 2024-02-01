<?php
/** Helper functions for side bars
 *
 */

function j3RandomPhoto( $category=NULL )
{
    $rand_post_args = array(
        'post_type' => 'photo_album',
        'orderby' => 'rand',
        'tax_query' => array(
            j3StdPhotosQuery(),
        ),
        'posts_per_page' => 1
    );
    if (! is_null($category)) {
        $rand_post_args["cat"] = $category;
    }
    if (function_exists('j3_date_month_query')) {
        // Warning some categories may not have anything in summer months
        $this_month = date('m');
        $rand_post_args['meta_query'] = array(
            j3_date_month_query($this_month),
        );
    }

    // Get the gallery post
    $query_parent = new WP_Query( $rand_post_args );
    $parent_id = False;
    if (! $query_parent->have_posts()
        && array_key_exists('meta_query', $rand_post_args)) {
        // try again without month restriction
        wp_reset_postdata();
        unset($rand_post_args['meta_query']);
        $query_parent = new WP_Query( $rand_post_args );
    }
    if ($query_parent->have_posts()) {
        $query_parent->the_post();
        $parent_id = get_post()->ID;
        $parent_name = get_post()->post_name;
    }
    wp_reset_postdata(); 

    if (! $parent_id) {
        return;
    }

    $args = array(
        'post_parent' => $parent_id,
        'posts_per_page' => 1,
        'post_status' => 'inherit',
        'post_type' => 'attachment',
        'post_mime_type' =>'image',
        'orderby' => 'rand',
    );

    // use back references from galleries. From j3gallery plugin
    $full_post_id = $parent_id;
    if (function_exists('j3gallery_get_referrers')) {
        $referring_ids = j3gallery_get_referrers($parent_id);
        if ( $referring_ids) {
            // making an assumption here
            $full_post_id = $referring_ids[0];
        }
    }
    $parent_url = get_permalink($full_post_id);

    // Find a specific photo
    $query = new WP_Query( $args );
    if ($query->have_posts()) {
        $query->the_post();
        echo '<div class="displayPhoto dualShadow">';
        echo '<a href="';
        echo esc_url( $parent_url );
        echo '" class="photoLink">';
        echo wp_get_attachment_image(get_post()->ID, 'large');
        echo '</a>';
        echo '</div>';
    }
    wp_reset_postdata(); 
}

function j3CtaBox()
{
    if ( is_active_sidebar('cta_box') ) {
        echo '<div class="visualPage widgetPage">';
        dynamic_sidebar( 'cta_box' );
        echo '</div>';
    }
}

function j3RecentGalleries( $category=NULL, $tag=NULL )
{
    $args = array(
        'post_type' => 'photo_album',
        'posts_per_page' => 7
    );
    if (! is_null($category)) {
        $args["cat"] = $category;
    }
    if (! is_null($tag)) {
        $args["tag"] = $tag;
    }
    $query = new WP_Query( $args );
    if ($query->have_posts()) {
        echo '<h1 class="topicTitle">Photo Albums</h1>';
        while ( $query->have_posts() ) {
                $query->the_post();
                set_query_var('display_post', 'summary');
                if (get_post_type() == 'photo_album') {
                    $format = 'gallery';
                } else {
                    $format = get_post_format();
                }
                get_template_part( 'card', $format ); 
        }
        $photos_url = get_post_type_archive_link('photo_album');
        if (! is_null($category)) {
            $photos_url = add_query_arg('cat', 
                $category,
                $photos_url);
        }
        if (! is_null($tag)) {
            $photos_url = add_query_arg('tag',
                $tag,
                $photos_url);
        }
        echo '<div class="albumText">
            <a href="' . $photos_url
            . '">More Photos ...</a>
                </div>';
        /* Restore original Post Data */
        wp_reset_postdata();
    }
}

