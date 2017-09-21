<?php
function j3HouseTempImg () {
    echo '
                <div class="rightContent">
                    <div class="visualPage displayPhoto">
                        <a href="/house/" class="photoLink">
                            <img src="/house/oneWeekTemps-basic.png" alt="latest temperatures"/>
                        </a>
                        <p>
                        <a href="/house/">
                            Live temperature plots
                        </a>
                        </p>
                    </div> 
                </div> <!-- rightContent -->';
}

/* Fancy photo code */
function j3FancyPhoto () {
    $args = array(
        'post_type' => 'post',
        'tax_query' => array(
            array(
                'taxonomy' => 'post_format',
                'field' => 'slug',
                'terms' => array( 'post-format-image' )
            )
        ),
        'orderby' => 'rand',
        'posts_per_page' => 1
    );
    if (is_category() ) {
        $args["cat"] = get_query_var('cat');

        // See if this is house, and we should display a special heading
        $fullCat = get_category ($args["cat"]);
        if ($fullCat->slug == "house") {
            j3HouseTempImg();
            return;
        }
    }

    $query = new WP_Query( $args );
    if ($query->have_posts()) {
        $query->the_post();
        echo '<div class="rightContent">
            <div class="displayPhoto dualShadow">';
        j3PostThumbnail('large', true);
        echo '</div> 
            </div> <!-- rightContent -->';
    }
}

function j3TopicTitle() {
    if (is_category() ) {
        echo '<h1 class="topicTitle">';
        single_cat_title(); 
        echo '</h1>';
    }
    // not supporting tags, dates, etc right now
}

function j3FancyHeader() {
    if (is_single()) return;
    echo '<div class="hgroup">';
    j3FancyPhoto();
    j3TopicTitle();
    echo '</div> <!-- hgroup -->';
    return;
}
