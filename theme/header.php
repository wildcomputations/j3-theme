<?php
/** The header for j3 custom theme
 * Displays all of the head section and the header inside the body
 */
function j3MainNav()
{
?>
    <nav id="mainMenu">
        <a class="responsiveMenu"
           onclick="toggleBySelector('#mainMenu > ul')" href="#">
              Menu
        </a>
        <?php 
        wp_nav_menu( array(
                'container' => 'false',
                'depth' => 2,
                'theme_location' => 'primary',
        ) );
        ?>
    </nav>
<?php
}

function j3Header()
{
?>
        <header>
            <h1 class="siteTitle"><a href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a></h2>
            <h2><?php bloginfo( 'description' ); ?></h2>
<?php if (!j3IsFancyPhoto() ) : ?>
                <?php get_search_form(); ?>
                <?php j3MainNav(); ?>
<?php endif; ?>
        </header>
<?php
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width">
        <meta charset="<?php bloginfo('charset'); ?>">
        <title><?php wp_title('|', true, 'right');  bloginfo('name'); ?></title>
        <meta property="og:title" content="<?php the_title(); ?>" />
        <meta property="og:url"
            content="<? echo get_page_link(); ?>" />
        <meta property="og:description"
            content="<?php echo get_the_excerpt(); ?>" />
        <meta property="og:site_name"
            content="<?php bloginfo('name'); ?>" />
<?php
if ( is_singular( 'post' ) ) {
?>
        <meta property="og:type" content="article" />
<?php
} else {
?>
        <meta property="og:type" content="website" />
<?php
}

if (has_post_thumbnail()) {
    $post_thumbnail_id = get_post_thumbnail_id( );
    $img_data = wp_get_attachment_image_src($post_thumbnail_id,
        'post-thumbnail');
    if ($img_data) {
        list($src, $width, $height) = $img_data;
        echo '<meta property="og:image" content="' . $src .  '" />';
    }
}
?>
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <?php j3Header(); ?>
