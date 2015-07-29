/* Menu Implementations */
function mainMenuResize( )
{
    var menu = jQuery("#mainMenu");
    var responsiveMenu = menu.children(".responsiveMenu");
    if (responsiveMenu.is(":visible")) {
        menu.css(
                {
                    height: "initial"
                });
        return;
    }
    
    /* also make sure no responsive styles are set */
    var allSubMenus = menu.children("ul");
    var topIndex;
    for (topIndex = 0; topIndex < allSubMenus.length; topIndex++) {
        var list = allSubMenus[topIndex];
        list.removeAttribute("style");
    }

    var topHeight = menu.children("ul").height();
    bottom = menu.find("ul .current-menu-ancestor ul");
    if (bottom.length == 0) {
        bottom = menu.find("ul .current-menu-item ul");
    }
    if (bottom.length == 0) {
        bottom = menu.find("ul .current-page-ancestor ul");
    }
    if (bottom.length == 0) bottomHeight = 0;
    else bottomHeight = bottom.height() - 1; // fudge for firefox
    //console.log("Top height %d bottom height %d", topHeight, bottomHeight);
    menu.css ( 
            { 
                height: topHeight + bottomHeight
            });

}

function menuClick(menuId)
{
    var menuItem = jQuery("#" + menuId);
    var allMenuItems = menuItem.parent().children();
    allMenuItems.removeClass(
            "current-menu-item current-menu-ancestor "
            + "current-page-ancestor");

    menuItem.addClass("current-menu-item");
    mainMenuResize();

}

function populateMenuClicks()
{
    var topMenu = jQuery("#mainMenu");
    var allSubMenus = topMenu.find("ul");
    var topIndex;
    for (topIndex = 0; topIndex < allSubMenus.length; topIndex++) {
        var menu = allSubMenus[topIndex];
        var itemIndex;
        for (itemIndex = 0; itemIndex < menu.children.length;
                ++itemIndex) {
            var item = menu.children[itemIndex];
            var link = item.querySelector("a");
            function createClickFunc(id, retVal) {
                return function() {
                    menuClick(id);
                    return retVal;
                }
            }
            // has children, stop click from switching pages
            if (item.getElementsByTagName("ul").length != 0) {
                link.onclick = createClickFunc(item.id, false);
            } else {
                link.onclick = createClickFunc(item.id, true);
            }

        }
    }
}

$expandBox = jQuery("#pushFooter");
$heightNoPush = jQuery(document.body).height() - $expandBox.height();
function sizeWindow() {
    if ( $heightNoPush < jQuery(window).height() ) {
        $expandBox.css({
            height: jQuery(window).height() - $heightNoPush
        })
    } else {
        $expandBox.css({
            height: "0"
        })
    }

}

function j3ResizeWindow() 
{
    mainMenuResize();
    sizeWindow();
}

jQuery(function ($) {

    $(document).ready(function(){
        mainMenuResize();
    });


    // Window load event used just in case window height is dependant upon images
    $(window).bind("load", function() { 
           
               
           $(window)
                   .scroll(sizeWindow)
                   .resize(j3ResizeWindow);


           populateMenuClicks();
           j3ResizeWindow();
    });
});


/* Gallery collapsing */
function j3Show(moreObj) {
    var $galleryObj = moreObj.parentNode;
    var $hideElements = $galleryObj.getElementsByClassName("collapseHide");
    for (var i = 0 ; i < $hideElements.length; ++i) {
        $hideElements.item(i).style.display = "inline-block";
    }
    var $showElements = $galleryObj.getElementsByClassName("collapseShow");
    for (var i = 0 ; i < $showElements.length; ++i) {
        $showElements.item(i).style.display = "none";
    }
}

function j3Hide(lessObj) {
    var $galleryObj = lessObj.parentNode;
    var $hideElements = $galleryObj.getElementsByClassName("collapseHide");
    for (var i = 0 ; i < $hideElements.length; ++i) {
        $hideElements.item(i).style.display = "none";
    }
    var $showElements = $galleryObj.getElementsByClassName("collapseShow");
    for (var i = 0 ; i < $showElements.length; ++i) {
        $showElements.item(i).style.display = "inline-block";
    }
}

function toggleBySelector(selector) {
    var options = jQuery(selector);
    if (options.is(":visible")) {
        options[0].removeAttribute("style");
    } else {
        options[0].style["display"] = "block";
    }
}

