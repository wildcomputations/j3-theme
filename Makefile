themeName = j3Custom

jsFiles = baseScripts.js
standardTemplates = index.php \
		    home.php \
		    front-page.php \
		    category.php \
		    taxonomy-post_format-post-format-gallery.php \
		    date.php \
		    page.php \
                    single.php
pageTemplates = archives.php 
phpFiles = $(standardTemplates) $(pageTemplates) \
	   functions.php \
	   header.php \
	   footer.php \
	   searchform.php \
	   search.php \
	   excerpt.php \
	   excerpt-gallery.php \
	   excerpt-image.php \
	   full.php \
	   full-gallery.php \
	   full-image.php \
	   card.php \
	   card-gallery.php \
	   content-none.php \
	   comments.php
styleSheets = style.css 
files = $(jsFiles) $(phpFiles) $(styleSheets) screenshot.png
images = arrows.png feedicons-standard/feed-icon-14x14.png lightpaperfibers.png toolbar_find.png green_cup.png seamlesstexture15_500.jpg pageTurn.png bookBinding.png
fontAwesome = $(themeName)/font-awesome-4.4.0/css/font-awesome.min.css

installedFiles = $(patsubst %,$(themeName)/%,$(files)) 
installedImages = $(patsubst %,$(themeName)/images/%,$(images)) 

all: $(themeName).zip

$(installedFiles) : $(themeName)/% : %
	mkdir -p $(themeName)       
	cp $< $@

$(installedImages) : $(themeName)/% : %
	mkdir -p $(dir $@)
	cp $< $@

font-awesome-4.4.0.zip:
	wget http://fortawesome.github.io/Font-Awesome/assets/font-awesome-4.4.0.zip &> download.log

$(fontAwesome): font-awesome-4.4.0.zip
	mkdir -p $(themeName)
	unzip -u -o -d $(themeName) $< 


$(themeName).zip: $(installedFiles) $(installedImages) $(fontAwesome)
	zip -r $@ $(themeName)

.phony: clean install
clean:
	rm -rf $(themeName)

installDir = /volume1/web/wordpress/wp-content/themes/
install: $(themeName).zip
	scp $< emilie@mansfield.local:$(installDir)
	ssh emilie@mansfield.local \
		 unzip -u -o -d $(installDir) $(installDir)/$(themeName).zip

j3HomeDir = /home/j3test31
j3InstallDir = $(j3HomeDir)/public_html/wp-content/themes/
install-j3: $(themeName).zip
	scp $< j3.org:
	ssh j3.org \
		 unzip -u -o -d $(j3InstallDir) $(j3HomeDir)/$(themeName).zip 
	ssh j3.org rm $(j3HomeDir)/$(themeName).zip
