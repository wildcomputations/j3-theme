<?php
/**
 * @package j3Custom
 */
if (!function_exists('j3ContentExcerpt') ) :

// Everything that goes in the article
function j3ContentExcerpt() {
    echo '<article class="visualPage postExcerpt ">';

    j3PostThumbnail('medium', true);
    echo '<h1 class="articleTitle"><a href="' . get_permalink() . '">';
    the_title();
    echo '</a></h1>';
    if (get_post_type() == 'post' ) {
        echo '<div class="date">';
        $trip_date = j3_date_post('M j, Y');
        if (!empty($trip_date)) {
            echo $trip_date . '<br>';
        }
        echo 'Author ' . get_the_author();
        echo '</div>';
    }
    the_excerpt();
    echo '<p><a class="moretag" href="'. get_permalink() . '"> Read Full Post</a></p>';
    echo '<p class="date alignright">Posted ' . get_the_date('M j, Y') . '</p>';
    echo '</article>';
}

endif; // function declarations

if ( post_password_required() ) {
    echo '<div class="hgroup hasPage">
            <div class="rightContent">
                <p>This post requires authentication</p>
            </div>
        </div>';
	return;
}

if (is_front_page()) {
    j3ContentExcerpt();

} else { ?>

<div class="hgroup hasPage">
    <div class="rightContent">
        <?php j3ContentExcerpt(); ?>
    </div> <!-- rightContent -->
</div> <!-- hgroup -->

<?php
} ?>
