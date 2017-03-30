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
        echo '</p>';
        $trip_date = j3_date_post('M j, Y');
        if (!empty($trip_date)) {
            echo '<p class="date">' . $trip_date . '</p>';
        }
        echo '</div>';
?>
