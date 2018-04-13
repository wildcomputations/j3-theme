j3mailchimp.zip: j3mailchimp.php style.css
	rm -rf j3mailchimp/
	mkdir j3mailchimp/
	cp $^ j3mailchimp/
	zip -r $@ j3mailchimp/
	rm -rf j3mailchimp/

installDir = /Applications/MAMP/htdocs/wordpress/wp-content/plugins/
install: j3mailchimp.zip
	unzip -u -o -d $(installDir) $<

j3HomeDir = /home/jorg
j3InstallDir = $(j3HomeDir)/public_html/wp-content/plugins/
install-j3: j3mailchimp.zip
	scp $< j3.org:
	ssh j3.org \
	    unzip -u -o -d $(j3InstallDir) $(j3HomeDir)/$<
	ssh j3.org rm $(j3HomeDir)/$<
