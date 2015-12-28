<?php
/**
 * Final items present on every page, starting with the copyright.
 *
 * @package j3Custom
 */
if (!is_front_page()) {?>

<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

    <!-- Background of PhotoSwipe. 
         It's a separate element as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>

    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">

        <!-- Container that holds slides. 
            PhotoSwipe keeps only 3 of them in the DOM to save memory.
            Don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <!--  Controls are self-explanatory. Order can be changed. -->

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader--active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div> 
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

        </div>

    </div>

</div>

<?php } ?>

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
