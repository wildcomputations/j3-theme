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
            <?php j3MainNav(); ?>
            <h1 class="siteTitle"><a href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a></h2>
            <h2><?php bloginfo( 'description' ); ?></h2>
            <?php get_search_form(); ?>
<?php if (!j3IsFancyPhoto() ) : ?>
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
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <?php j3Header(); ?>
