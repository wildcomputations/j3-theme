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

expandBox = jQuery("#pushFooter");
heightNoPush = jQuery(document.body).height() - expandBox.height();
function sizeWindow() {
    if ( heightNoPush < jQuery(window).height() ) {
        expandBox.css({
            height: jQuery(window).height() - heightNoPush
        })
    } else {
        expandBox.css({
            height: "0"
        })
    }

}

function sizeSearch() {
    var searchRight = jQuery("#searchRight");
    var searchBar = jQuery("#searchBar");
    if (searchRight) {
        print 
        if (jQuery(window).width() <= 800) {
            searchRight.css({
                width: searchRight.parent().width() - 11
            });
        } else {
            searchRight.css({
                width: searchRight.parent().width() - searchBar.outerWidth() - 11
            });
        }
    }
}

function j3ResizeWindow() 
{
    mainMenuResize();
    sizeWindow();
    sizeSearch();
}

jQuery(function () {

    jQuery(document).ready(function(){
        mainMenuResize();
        galleryInit();
        singlePhotosInit();
    });


    // Window load event used just in case window height is dependant upon images
    jQuery(window).bind("load", function() { 
           
               
           jQuery(window)
                   .scroll(sizeWindow)
                   .resize(j3ResizeWindow);


           populateMenuClicks();
           j3ResizeWindow();
    });
});


/* Gallery collapsing */
function j3Show(moreObj) {
    var galleryObj = moreObj.parentNode;
    var hideElements = galleryObj.getElementsByClassName("collapseHide");
    for (var i = 0 ; i < hideElements.length; ++i) {
        hideElements.item(i).style.display = "inline-block";
    }
    var showElements = galleryObj.getElementsByClassName("collapseShow");
    for (var i = 0 ; i < showElements.length; ++i) {
        showElements.item(i).style.display = "none";
    }
}

function j3Hide(lessObj) {
    var galleryObj = lessObj.parentNode;
    var hideElements = galleryObj.getElementsByClassName("collapseHide");
    for (var i = 0 ; i < hideElements.length; ++i) {
        hideElements.item(i).style.display = "none";
    }
    var showElements = galleryObj.getElementsByClassName("collapseShow");
    for (var i = 0 ; i < showElements.length; ++i) {
        showElements.item(i).style.display = "inline-block";
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

function toggleClassBySelector(selector, className) {
    var element = jQuery(selector);
    element.toggleClass(className);
}


/* Gallery interface to photoswipe */
// items - list of image objects with src, w (width), h (height)
// index - index of the photo to open first
function makeOpenLightbox(items, index) {
    return function() {
        var pswpElement = document.querySelectorAll('.pswp')[0];

        // define options (if needed)
        var options = {
            index: index,
            loop: false,
        };

        // Initializes and opens PhotoSwipe
        var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
        gallery.init();

        // Don't follow the href from the current event
        return false;
    };
}

/* extract size, url, and caption from dom elements 
 * \param size - element that should have data-width and data-height
 * \param link - length 0 or 1 array of link surrounding the image
 * \param caption - length 0 or 1 item containing a caption
 */
function lightBoxInfo(size, link, caption) {
    if (link.length == 0) {
        var item = {
            'w' : 1000,
            'h' : 500,
            'src' : window.location.hostname + "/404"
        };

        return item;
    }

    var item = {
        'w' : size.attr('data-width'),
        'h' : size.attr('data-height'),
        'src' : jQuery(link[0]).attr('href')
    };

    if (caption.length != 0) {
        item.title = jQuery(caption[0]).html();
    }

    return item;
}

/* extract the size, url, and caption from a gallery item (<dl>) */
function galleryItemInfo(galleryItem) {
    /* Get the image url */
    var link = galleryItem.find('a');

    /* Get the caption */
    var captionElements = galleryItem.find('dd.wp-caption-text');

    return lightBoxInfo(galleryItem, link, captionElements);
}

/* Parse the whole page to find galleries and set the onClick action for each
 * thumbnail to load the photo swipe lightbox */
function galleryInit() {
    var galleries = jQuery('.gallery');
    for (var i = 0; i < galleries.length; ++i) {
        var gallery = jQuery(galleries[i]);

        /* Create the list of items for this photo swipe gallery */
        var items = [];
        var pictures = gallery.children('dl.gallery-item');
        for (var j = 0; j < pictures.length; ++j) {
            var thumb = jQuery(pictures[j]);

            items.push(galleryItemInfo(thumb));
        }

        /* modify all the links to open the gallery lightbox */
        var links = gallery.find('a');
        for (var j = 0; j < links.length; ++j) {
            jQuery(links[j]).click(
                makeOpenLightbox(items, j)
            );
        }
    }
}

function singlePhotosInit() {
    var images = jQuery('[class*="wp-image-"]');
    for (var i = 0; i < images.length; ++i) {
        var image = jQuery(images[i]);

        /* get the parent link. If there is no parent, we can't open the image anyway */
        var links = image.parent('a[href$=".jpg"], a[href$=".jpeg"], a[href$=".png"]');
        if (links.length == 0) continue;

        var captionContainers = jQuery(links.parent('.wp-caption')).children('.wp-caption-text');

        var items = [lightBoxInfo(image, links, captionContainers)];

        jQuery(links[0]).click(
                makeOpenLightbox(items, 0)
                );
    }
}
