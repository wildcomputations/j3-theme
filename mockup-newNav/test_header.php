<?php

require 'framing.php';
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
        </style>
    </head>
    <body>
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
main_nav();
?>
        <div class="redBox"></div>
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
get_header();
?>
        <div class="redBox"></div>
        <div class="blueBox">
            <p>
            Harum congue bonorum qui id, id nullam petentium definitionem ius. 
            No vel tollit lobortis. Legere vulputate elaboraret ex nec, sea 
            lorem menandri in, vel assum denique at. Eius voluptua ex per, ne 
            has vocibus conceptam appellantur, cu iudico percipit 
            necessitatibus sea.
            <p>
        </div>
            <nav id="mainMenu">
                <ul class="current-menu-item"><ul>
<?php
mainSubMenu(array("First Item" => "#", "Second Item" => "#"));
?>
                </ul></ul>
            </nav>
        <div class="redBox"></div>
    </body>
</html>

