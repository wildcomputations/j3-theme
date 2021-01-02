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
} elseif (get_post_type() == 'attachment') {
    echo wp_get_attachment_image( get_the_ID(), 'thumbnail',
        "",
        array('class' => 'attachment-thumbnail size-thumbnail wp-post-image')
    );
} else {
    echo "Image needs photo";
}
echo '  </a>
    <div class="articleHead"><h1><a href="' . get_permalink() . '">' 
    . get_the_title() . '</a></h1></div>';
?>
<p>Single photo</p>
</article>
