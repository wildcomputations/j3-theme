<?php
/** Helper functions for side bars
 *
 */

function j3RandomPhoto( $category=NULL )
{
    $rand_post_args = array('post',
        'orderby' => 'rand',
        'tax_query' => array(
            array(
                'taxonomy' => 'post_format',
                'field' => 'slug',
                'terms' => array( 'post-format-gallery' )
            ),
            j3StdPhotosQuery()
        ),
        'posts_per_page' => 1
    );
    if (! is_null($category)) {
        $rand_post_args["cat"] = $category;
    }
    if (function_exists('j3_date_month_query')) {
        $this_month = date('m');
        $rand_post_args['meta_query'] = array(
            j3_date_month_query($this_month),
        );
    }

    $query_parent = new WP_Query( $rand_post_args );
    $parent_id = False;
    if ($query_parent->have_posts()) {
        $query_parent->the_post();
        $parent_id = get_post()->ID;
        $parent_name = get_post()->post_name;
        $parent_url = get_permalink($parent_id);
    }
    wp_reset_postdata(); 

    if ($parent_id) {
        $args = array(
            'post_parent' => $parent_id,
            'posts_per_page' => 1,
            'post_status' => 'inherit',
            'post_type' => 'attachment',
            'post_mime_type' =>'image',
            'orderby' => 'rand',
        );

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
}

function j3CtaBox()
{
    if ( is_active_sidebar('cta_box') ) {
        echo '<div class="visualPage widgetPage">';
        dynamic_sidebar( 'cta_box' );
        echo '</div>';
    }
}

function j3RecentGalleries()
{
    $args = array(
        'post_type' => 'post',
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'post_format',
                'field' => 'slug',
                'terms' => array( 'post-format-gallery' )
            ),
            j3StdPhotosQuery(),
        ),
        'posts_per_page' => 7
    );
    $query = new WP_Query( $args );
    if ($query->have_posts()) {
        echo '<h1 class="topicTitle">Photo Albums</h1>';
        while ( $query->have_posts() ) {
                $query->the_post();
                set_query_var('display_post', 'summary');
                get_template_part( 'card', get_post_format() ); 
        }
        echo '<div class="albumText">
            <a href="' . get_post_format_link(get_post_format())
            . '">More Photos ...</a>
                </div>';
        /* Restore original Post Data */
        wp_reset_postdata();
    }
}

