installDir = /Applications/MAMP/htdocs/wordpress/wp-content/plugins/
install: checker-plugin.php
	cp $< $(installDir)


j3HomeDir = /home/jorg
j3InstallDir = $(j3HomeDir)/public_html/wp-content/plugins/
install-j3: checker-plugin.php
	scp $< j3.org:$(j3InstallDir)
