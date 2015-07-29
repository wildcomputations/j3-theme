<?php
/** The header for j3 custom theme
 * Displays all of the head section and the header inside the body
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width">
        <meta charset="<?php bloginfo('charset'); ?>">
        <title><?php wp_title('|', true, 'right');  bloginfo('name'); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

        <?php wp_head(); ?>
    </head>
    <body <?php body_class() ?>>
        <header>
            <h1 class="siteTitle"><a href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a></h2>
            <h2><?php bloginfo( 'description' ); ?></h2>
<?php if (!j3IsFancyPhoto() ) : ?>
                <?php get_search_form(); ?>
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
<?php endif; ?>
        </header>

