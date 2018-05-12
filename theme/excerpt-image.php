<?php
/**
 * @package j3Custom
 */
?>

<article class="visualPage displayPhoto">
<?php 
echo '<a href="' . get_permalink() . '" class="photoLink">';
if (has_post_thumbnail()) {
    the_post_thumbnail( 'thumbnail' );
} else {
    echo "Image needs photo";
}
echo '  </a>
    <h1 class="articleTitle"><a href="' . get_permalink() . '">' 
    . get_the_title() . '</a></h1>';
?>
</article>
