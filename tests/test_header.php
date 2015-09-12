<?php

function j3IsFancyPhoto()
{
    return false;
}
function wp_nav_menu( $args )
{
?>
            <nav id="mainMenu">
                <a class="responsiveMenu" onclick="toggleBySelector('#mainMenu > ul')" href="#">Menu</a>
                <ul class="contains6">
                    <li id="menu-1"><a href="#" >Home</a>
                    <li id="menu-2" class="thisPage"><a href="#" >Browse Trip Reports</a>
                        <ul class="contains5">
                            <li id="submenu-1"><a href="#" >By Category</a>
                            <li id="submenu-2" class="thisPage"><a href="#" >By Year</a>
                            <li id="submenu-3"><a href="#" >By Club</a>
                            <li id="submenu-4"><a href="#" >Best Reports</a>
                            <li id="submenu-5"><a href="#" >Search</a>
                        </ul>
                    <li id="menu-3"><a href="#" >Photography</a>
                        <ul class="contains2">
                            <li id="submenu-6"><a href="#" >Photos</a>
                            <li id="submenu-7"><a href="#" >Videos</a>
                        </ul>
                    <li id="menu-4"><a href="#" >Plan A Trip</a>
                        <ul class="contains3">
                            <li id="submenu-8"><a href="#" >Favorite Trips</a>
                            <li id="submenu-9"><a href="#" >Clubs</a>
                            <li id="submenu-10"><a href="#" >Weather</a>
                        </ul>
                    <li id="menu-5"><a href="#" >Recent Trips</a>
                        <ul class="contains5">
                            <li id="submenu-11"><a href="#" >All</a>
                            <li id="submenu-12"><a href="#" >Flying and Airplanes</a>
                            <li id="submenu-13"><a href="#" >Back Country Skiing</a>
                            <li id="submenu-14"><a href="#" >Kayaking Trips</a>
                            <li id="submenu-15"><a href="#" >Weather</a>
                        </ul>
                    <li id="menu-6"><a href="#" >Solar House</a>
                        <ul class="contains3">
                            <li id="submenu-16"><a href="#" >About</a>
                            <li id="submenu-17"><a href="#" >Live Monitoring</a>
                            <li id="submenu-18"><a href="#" >Status Updates</a>
                        </ul>
                </ul>
            </nav>
<?php
}

function genBlueBox()
{
?>
        <div class="blueBox">
            <p>
            Harum congue bonorum qui id, id nullam petentium definitionem ius. 
            No vel tollit lobortis. Legere vulputate elaboraret ex nec, sea 
            lorem menandri in, vel assum denique at. Eius voluptua ex per, ne 
            has vocibus conceptam appellantur, cu iudico percipit 
            necessitatibus sea.
            <p>
        </div>
<?php
}

require 'wp_test_functions.php';

ob_start();
require '../header.php';
$ignore = ob_get_clean();
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="style.css"/>
        <title>Header Unit Test</title>
        <style>
            .blueBox, .redBox {
                width:100%;
                height: 50px;
                border 1px solid black;
                display: block;
                overflow: hidden;
            }
            .blueBox {
                background: #aaaaFF;
            }
            .blueBox p {
                vertical-align:bottom;
            }
            .redBox {
                background: #ffaaaa;
            }
            .testInfo {
                width: 100%;
                padding: 65px 50px 35px;
                display: block;
                background: white;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="testInfo">
            <p>
                For this test to pass, each block of blue and red should look exactly like these examples.  
Each test case is surrounded by the red and blue blocks.
            </p>
        </div>
        <div class="redBox"></div>
<?php
genBlueBox();
?>
        <div class="testInfo">
            <p>
                This tests just the nav bar. There should be no space between the blue and red boxes and the nav bar. The shaddow from the nav bar should overlap the red box.
            </p>
        </div>
<?php
genBlueBox();
j3MainNav();
?>
        <div class="redBox"></div>
        <div class="testInfo">
            <p>
                This tests the whole header. The spacing below the header should be such that the header shaddow does not overlap the red bar. The search box should be right justified.
            </p>
        </div>
<?php
genBlueBox();
j3Header();
?>
        <div class="redBox"></div>
<?php
genBlueBox();
?>
    </body>
</html>

