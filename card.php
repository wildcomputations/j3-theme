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

<div class="stackPaper">
    <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="noEmph">
        <div class="summary">
            <p class="date">
               <?php
        if (is_date()) {
            echo get_the_date('D') . '<br>' . get_the_date('j');
        } else {
            echo get_the_date('Y') . '<br>' . get_the_date('M');
        }?>
        </p>

    <?php if ( has_post_thumbnail()) {
        the_post_thumbnail($post->ID, 'thumbnail' );
    } ?>
    
        <h1><?php get_the_title();?></h1>
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
