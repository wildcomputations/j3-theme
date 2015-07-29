<?php
/**
 * @package j3Custom
 */
if (! function_exists('j3ContentImage' ) ):
function j3ContentImage( ) {
    if ( is_single() ) {
        the_post_thumbnail("full", array('class' => 'displayPhoto'));
        echo '<div class="displayPhoto photoData">
                <p>';
        the_title();
        echo '</p><p class="author">Photographer: ';
        the_author();
        echo '</p><p class="date">' . get_the_date('M j, Y') . '</p>
            </div>';
    } else {
        echo '<div class="rightContent">
            <div class="displayPhoto dualShadow">';
        j3PostThumbnail();
        echo '</div> 
            </div> <!-- rightContent -->';
    }
}

endif;
?>

<?php j3ContentImage() ?>
