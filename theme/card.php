<?php
/**
 * @package j3Custom
 *
 * Full view for gallery posts
 *
 * Date archives get day of week, and day of month.
 * Everything else gets year and month
 */
?>

// XXX This is broken for attachments as seen in search

<div class="stackPaper">
    <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="noEmph">
        <div class="summary"><?php
if (is_date() || j3_date_is_archive()) {
    $day = j3_date_post('D');
    if (!empty($day)) {
        $display_date = $day . '<br>' . j3_date_post('j');
    }
} else {
    $year = j3_date_post('Y');
    if (!empty($year)) {
        $display_date = $year . '<br>' . j3_date_post('M');
    }
}
if ( !empty($display_date)) {
    echo '<p class="date">' . $display_date . '</p>';
}
if ( has_post_thumbnail()) {
    the_post_thumbnail($post->ID, 'thumbnail' );
} ?>
    
        <h1><?php the_title();?></h1>
        <?php echo j3NoLinkCategories(); 
        if ( ! has_post_thumbnail()) {
            j3SetShortExcerpt();
            echo '<p>' . get_the_excerpt() . "</p>";
            // we don't expect nested post previews, so this can just be set to 
            // false rather than having to remember the previous setting.
            j3EndShortExcerpt();
        } ?>
    </div> <!-- summary --> 
  </a>
</div>
