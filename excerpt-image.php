<?php
/**
 * @package j3Custom
 */
?>

<div class="hgroup hasPage">
    <div class="rightContent">
        <article class="visualPage">
        <?php 
    echo '<a href="' . get_permalink() . '" class="photoLink">';
    if (has_post_thumbnail()) {
        the_post_thumbnail( 'thumbnail' );
    } else {
        echo "Image needs photo";
    }
    echo '  </a>
            <h1 class="articleTitle"><a href="' . get_permalink() . '">' 
                . get_the_title() . '</a></h1>
                <div class="date">' . get_the_date('M j, Y') . '</div>';
    the_excerpt();
    ?>
         </article>
     </div><!-- rightContent -->
</div> <!-- hgroup -->
