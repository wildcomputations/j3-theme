
function mainMenuResize( )
{
    /*$("#mainMenu").css( "border", "3px solid red" );*/
    var menu = $("#mainMenu");
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
    bottom = menu.find("ul .current-menu-ancester ul");
    if (bottom.length == 0) bottomHeight = 0;
    else bottomHeight = bottom.height() - 1; // fudge for firefox
    menu.css ( 
            { 
                height: topHeight + bottomHeight
            });

}

function toggleBySelector(selector) {
    var options = $(selector);
    if (options.is(":visible")) {
        options[0].removeAttribute("style");
    } else {
        options[0].style["display"] = "block";
    }
}

function toggleClassBySelector(selector, className) {
    var element = $(selector);
    element.toggleClass(className);
}

function menuClick(menuId)
{
    var menuItem = $("#" + menuId);
    var allMenuItems = menuItem.parent().children();
    allMenuItems.removeClass("current-menu-item current-menu-ancester");

    menuItem.addClass("current-menu-item current-menu-ancester");
    mainMenuResize();

}

function populateMenuClicks()
{
    var topMenu = $("#mainMenu");
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

// Window load event used just in case window height is dependant upon images
$(window).bind("load", function() { 
       
       var $expandBox = $("#pushFooter");
       var $heightNoPush = $(document.body).height() - $expandBox.height();
           
       sizeWindow();
       
       function sizeWindow() {
           if ( $heightNoPush < $(window).height() ) {
                $expandBox.css({
                    height: $(window).height() - $heightNoPush
                })
           } else {
                $expandBox.css({
                    height: "0"
                })
           }

           mainMenuResize();
       
       }

       $(window)
               .scroll(sizeWindow)
               .resize(sizeWindow)
               
       populateMenuClicks();
});

