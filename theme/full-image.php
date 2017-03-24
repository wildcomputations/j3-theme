<?php
/**
 * @package j3Custom
 */
        the_post_thumbnail("full", array('class' => 'displayPhoto'));
        echo '<div class="displayPhoto photoData">
                <p>';
        the_title();
        echo '</p><p class="author">Photographer: ';
        the_author();
        echo '</p><p class="date">' . get_the_date('M j, Y') . '</p>
            </div>';
?>
