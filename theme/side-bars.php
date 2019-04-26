<?php
/** Helper functions for side bars
 *
 */

function j3GallerySearch( $term_id=NULL, $taxonomy='category')
{
    $tax_query = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'post_format',
                'field' => 'slug',
                'terms' => array( 'post-format-gallery' )
            ),
            j3StdPhotosQuery()
        );
    if (! is_null($term_id)) {
        $tax_query[] = array(
            'taxonomy' => $taxonomy,
            'field' => 'term_id',
            'terms' => array( $term_id )
        );
    }

    $post_args = array(
        'post_type' => 'post',
        'tax_query' => $tax_query,
        'posts_per_page' => 1
    );

    return $post_args;
}

function j3RandomPhotoSearch( $term_id=NULL, $taxonomy='category' )
{
    $rand_post_args = j3GallerySearch($term_id, $taxonomy);
    $rand_post_args['orderby'] = 'rand';
    if (function_exists('j3_date_month_query')) {
        // Warning some categories may not have anything in summer months
        $this_month = date('m');
        $rand_post_args['meta_query'] = array(
            j3_date_month_query($this_month),
        );
    }

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
        $parent_url = get_permalink($parent_id);
    }
    wp_reset_postdata(); 

    $result = NULL;
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
            $result = array('parent_url' => $parent_url,
                'id' => get_post()->ID);
        }
        wp_reset_postdata(); 
    }
    return $result;
}

function j3RandomPhoto( $term_id=NULL, $taxonomy='category' )
{
    $photo_meta = j3RandomPhotoSearch($term_id, $taxonomy);
    if (!is_null($photo_meta)) {
        echo '<div class="displayPhoto dualShadow">';
        echo '<a href="';
        echo esc_url( $photo_meta['parent_url'] );
        echo '" class="photoLink">';
        echo wp_get_attachment_image(
            $photo_meta['id'],
            get_post()->ID, 'large');
        echo '</a>';
        echo '</div>';
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

function j3RecentGalleries( $term_id=NULL, $taxonomy='category' )
{
    $args = j3GallerySearch($term_id, $taxonomy);
    $args['posts_per_page'] = 7;
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

