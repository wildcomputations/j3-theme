installDir = /Applications/MAMP/htdocs/wordpress/wp-content/plugins/
install: gallery-ref-plugin.php
	cp $< $(installDir)


j3HomeDir = /home/jorg
j3InstallDir = $(j3HomeDir)/public_html/wp-content/plugins/
install-j3: gallery-ref-plugin.php
	scp $< j3.org:$(j3InstallDir)
