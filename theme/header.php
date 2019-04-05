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
                'menu_class' => 'primaryMenu',
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
<?php if (!j3IsFancyPhoto() ) {
            the_custom_logo();
} ?>
            <h1 class="siteTitle"><a href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a></h2>
            <br>
            <h2><?php bloginfo( 'description' ); ?></h2>
<?php if (!j3IsFancyPhoto() ) : ?>
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
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <?php j3Header(); ?>
