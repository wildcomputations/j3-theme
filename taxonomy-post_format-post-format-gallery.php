<?php
/**
 * Special template file for post_format taxonomies of type gallery.
 *
 * @package j3Custom
 */

function j3GalleryPrevPage()
{
    $pageOption = get_option('j3SetPhotoPage', "");
    if (!$pageOption) return "";
    return get_site_url() . "/" . $pageOption . "/";
}

get_header(); ?>

<div class="main"><!-- safari appears to not support main-->
    <?php 
        if ( have_posts() ) :
            echo '<div class="hgroup hasPage">';
            j3PageNav(j3GalleryPrevPage()); 
            while ( have_posts() ) {
                the_post(); 
                j3ArchiveDoYear();
                j3ArchiveDoMonth();
                get_template_part( 'card', get_post_format() ); 
            } ?>
        </div> <!-- month -->
    </div> <!-- rightContent -->
    </div> <!-- hgroup -->
<?php
            j3PageNav(j3GalleryPrevPage(), "", $standalone = true);
        else : 
            get_template_part( 'content', 'none' ); 
        endif; ?>

</div><!--main-->

<?php get_footer(); ?>
