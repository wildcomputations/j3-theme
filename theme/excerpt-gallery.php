<?php
/**
 * @package j3Custom
 */
require 'full-functions.php';

if (! function_exists('j3GalleryExcerpt' ) ):
function j3GalleryExcerpt() 
{
    echo '<article class="albumExcerpt hasStack visualPage">
          <div class="stackPhoto">
            <a href="' . get_permalink() . '" class="photoLink">';
    if (has_post_thumbnail()) {
        the_post_thumbnail( 'thumbnail' );
    } else {
        echo "Album needs summary photo";
    }
    echo '  </a>
            </div> <!-- stacks-->';
    j3ArticleHead(False, "Photo Album");
    the_excerpt();
    j3ArticleFooter();
    echo ' </article>';
}

endif;

j3GalleryExcerpt();
