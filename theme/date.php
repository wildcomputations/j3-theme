<?php
/**
 * The archive by date
 *
 * @package j3Custom
 */

get_header(); ?>

<div class="main"><!-- safari appears to not support main-->
    <?php 
        if ( have_posts() ) :
            echo '<div class="hgroup hasPage">';
            j3PageNav(); 
            while ( have_posts() ) {
                the_post(); 
                j3ArchiveDoYear();
                j3ArchiveDoMonth();
                if (get_post_type() == 'photo_album') {
                    $format = 'gallery';
                } else {
                    $format = get_post_format();
                }
                get_template_part( 'card', $format ); 
            } ?>
        </div> <!-- month -->
    </div> <!-- rightContent -->
    </div> <!-- hgroup -->
<?php
            j3PageNav("", "", $standalone = true);
        else : 
            get_template_part( 'content', 'none' ); 
        endif; ?>

</div><!--main-->

<?php get_footer(); ?>
