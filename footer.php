<?php
/**
 * Final items present on every page, starting with the copyright.
 *
 * @package j3Custom
 */
?>

        <div id="pushFooter"></div>
        <footer>
            <p>
            Copyright Tyson Sawyer and Emilie Phillips. 
            <span class="alignright">
                <!-- for main page, just "RSS feed" -->
                <?php
                if ( is_category() ) {
                  // retrieve current category object
                  $category = get_category( get_query_var('cat') );
                  if ( ! empty( $category ) ) {
                    echo '<a class="rssLink" href="' . get_category_feed_link( $category->cat_ID ) . '" title="Subscribe to ' . $category->name . '" rel="nofollow">'.$category->name . ' RSS feed</a>';
                  }
                  echo '<a class="rssLink" href="';
                  bloginfo('rss2_url');
                  echo '" title="Subscribe to all posts" rel="nofollow">All topics RSS feed</a>';
                } else if (!j3IsFancyPhoto()) {
                  echo '<a class="rssLink" href="';
                  bloginfo('rss2_url');
                  echo '" title="Subscribe" rel="nofollow">RSS feed</a>';
                }
?>

           </span>
            </p>
        </footer>
        <?php wp_footer(); ?>
    </body>
</html>
