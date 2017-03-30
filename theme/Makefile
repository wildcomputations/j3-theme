themeName = j3Custom

jsFiles = baseScripts.js
standardTemplates = index.php \
		    home.php \
		    front-page.php \
		    category.php \
		    date.php \
		    page.php \
                    single.php
pageTemplates = page-templates/archives.php \
	   page-templates/random-template.php \
	   page-templates/search.php \
	   page-templates/trip-date.php
phpFiles = $(standardTemplates) \
	   functions.php \
	   header.php \
	   footer.php \
	   searchform.php \
	   excerpt.php \
	   excerpt-gallery.php \
	   excerpt-image.php \
	   full.php \
	   full-gallery.php \
	   full-image.php \
	   card.php \
	   card-gallery.php \
	   content-none.php \
	   comments.php \
	   meta-boxes.php
styleSheets = style.css 
files = $(jsFiles) $(phpFiles) $(styleSheets) screenshot.png
images = arrows.png feedicons-standard/feed-icon-14x14.png lightpaperfibers.png toolbar_find.png green_cup.png seamlesstexture15_500.jpg pageTurn.png bookBinding.png
fontAwesomeVersion = 4.6.3
fontAwesome = $(themeName)/font-awesome-$(fontAwesomeVersion)/css/font-awesome.min.css
photoSwipe = $(themeName)/photoswipe/photoswipe.css

installedFiles = $(patsubst %,$(themeName)/%,$(files)) 
installedPageTemplates = $(patsubst page-templates/%,$(themeName)/%,$(pageTemplates))
installedImages = $(patsubst %,$(themeName)/images/%,$(images)) 

all: $(themeName).zip

$(installedFiles) : $(themeName)/% : %
	mkdir -p $(dir $@)
	cp $< $@

$(installedPageTemplates) : $(themeName)/% : page-templates/%
	mkdir -p $(dir $@)
	cp $< $@

$(installedImages) : $(themeName)/% : %
	mkdir -p $(dir $@)
	cp $< $@

font-awesome-$(fontAwesomeVersion).zip:
	wget https://fortawesome.github.io/Font-Awesome/assets/font-awesome-$(fontAwesomeVersion).zip &> download.log

$(fontAwesome): font-awesome-$(fontAwesomeVersion).zip
	mkdir -p $(themeName)
	unzip -u -o -d $(themeName) $< 

master.zip:
	wget https://github.com/dimsemenov/PhotoSwipe/archive/master.zip

$(photoSwipe): master.zip
	mkdir -p $(themeName)
	unzip -u -o -d /tmp/ $< PhotoSwipe-master/dist/*
	cp -a /tmp/PhotoSwipe-master/dist $(themeName)/photoswipe/

$(themeName).zip: $(installedFiles) $(installedPageTemplates) $(installedImages) $(fontAwesome) $(photoSwipe)
	zip -r $@ $(themeName)

.phony: clean install
clean:
	rm -rf $(themeName)

installDir = /Applications/MAMP/htdocs/wordpress/wp-content/themes/
install: $(themeName).zip
	unzip -u -o -d $(installDir) $(themeName).zip

j3HomeDir = /home/jorg
j3InstallDir = $(j3HomeDir)/public_html/wp-content/themes/
install-j3: $(themeName).zip
	scp $< j3.org:
	ssh j3.org \
		 unzip -u -o -d $(j3InstallDir) $(j3HomeDir)/$(themeName).zip 
	ssh j3.org rm $(j3HomeDir)/$(themeName).zip
