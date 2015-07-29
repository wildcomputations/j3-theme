<?php

if ( ! function_exists("start_page") ) {
    $uniqueMenuId = 0;
    function mainSubMenu($items, $currentName = "") {
        global $uniqueMenuId;

        echo '<ul>';
        foreach ($items as $name => $url) {
            echo '<li id="automenu-' . $uniqueMenuId . '" class="';
            if ($currentName == $name) echo 'current-menu-item ';
            echo 'item-count-' . count($items);
            echo '"><a href="' . $url . '">' . $name . '</a>';
            $uniqueMenuId += 1;
        }
        echo '</ul>';
    }

    function main_nav() {
?>
            <nav id="mainMenu">
                <a class="responsiveMenu" onclick="toggleBySelector('#mainMenu > ul')" href="#">Menu</a>
                <ul>
                    <li id="menu-1" class="item-count-6 current-menu-ancester current-menu"><a href="index.php" >Home</a>
                    <li id="menu-2" class="item-count-6"><a href="#" >Browse Trip Reports</a>
<?php mainSubMenu(
    array(
        "By Category" => "#",
        "By Year" => "#",
        "By Club" => "#",
        "Best Reports" => "#",
        "Search" => "#"
    ),
    "By Year");
?>
                    <li id="menu-3" class="item-count-6"><a href="#" >Photography</a>
<?php mainSubMenu(
    array(
        "Photos" => "#",
        "Videos" => "#"
    )
);
?>
                    <li id="menu-4" class="item-count-6"><a href="#" >Plan A Trip</a>
<?php mainSubMenu(
    array(
        "Favorite Trips" => "#",
        "Clubs" => "#",
        "Weather" => "#"
    )
);
?>
                    <li id="menu-5" class="item-count-6"><a href="#" >Recent Trips</a>
<?php mainSubMenu(
    array(
        "All" => "blog.php",
        "Flying and Airplanes" => "#",
        "Back Country Skiing" => "#",
        "Kayaking Trips" => "#",
        "Weather" => "#"
    )
);
?>
                    <li id="menu-6" class="item-count-6"><a href="#" >Solar House</a>
<?php mainSubMenu(
    array(
        "About" => "#",
        "Live Monitoring" => "#",
        "Status Updates" => "#"
    )
);
?>
                </ul>
            </nav>
<?php
    }

    function get_header() {
?>
        <header>
            <h1>Tyson, Emilie & Isaac</h1>
            <h2>Family Adventures</h2>
            <nav class="minorMenu">
                <ul>
                    <li><a href="#">About</a>
                    <li><form class="searchForm" action="http://j3.org/" method="get" role="search">
                        <label>
                            <input class="search-field" type="search" name="s" value="" placeholder="Search ..."/>
                        </label>
                        <button class="search-submit" type="submit">
                            <img src="../images/toolbar_find.png" alt-text="Search"/>
                        </button>
                    </form>
                </ul>
            </nav>
<?php
        main_nav();
?>

        </header>
<?php
    }

    function start_page() {
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width">
        <meta charset="UTF-8">
        <title>Tyson, Emilie & Isaac</title>
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
<?php
        get_header();
    }
}

if ( ! function_exists("get_footer") ) {
    function get_footer() {
?>
        <div id="pushFooter">
        </div>
        <footer>
            <p>
            Copyright Tyson and Emilie. 
            <span class="alignright">
                <a class="rssLink" href="feed">Hiking and Backpacking RSS feed</a>
                <a class="rssLink" href="feed">All topics RSS feed </a>
                <!-- for main page, just "RSS feed" -->
           </span>
            </p>
        </footer>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
        <script src="baseScripts.js"></script>
    </body>
</html>
<?php
    }
}
