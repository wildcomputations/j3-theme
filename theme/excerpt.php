<?php
/**
 * @package j3Custom
 */
require 'full-functions.php';

if (!function_exists('j3ContentExcerpt') ) :

// Everything that goes in the article
function j3ContentExcerpt() {
    echo '<article class="visualPage postExcerpt ">';

    j3PostThumbnail('medium', true);
    $is_post = get_post_type() == 'post';
    j3ArticleHead($is_post);
    the_excerpt();
    echo '<p><a class="moretag" href="'. get_permalink() . '"> Read Full Post</a></p>';
    if (get_post_type() == 'post' ) {
        j3ArticleFooter();
    }
    echo '</article>';
}

endif; // function declarations

if ( post_password_required() ) {
    echo '<p>This post requires authentication</p>';
}

j3ContentExcerpt();
?>
