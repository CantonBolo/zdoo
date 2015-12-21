VERSION=$(shell head -n 1 VERSION)

all: ranzhi
clean:
	rm -fr ranzhi
	rm -fr *.zip
ranzhi:
	mkdir ranzhi
	cp -fr app ranzhi/
	cp -fr bin ranzhi/
	cp -fr config ranzhi/ && rm -fr ranzhi/config/my.php
	mkdir ranzhi/db
	cp -fr db/*.sql ranzhi/db/
	cp -fr doc ranzhi/ && rm -fr ranzhi/doc/phpdoc && rm -fr ranzhi/doc/doxygen
	cp -fr framework ranzhi/
	cp -fr lib ranzhi/
	cp -fr tmp ranzhi
	rm -fr ranzhi/db/temp.sql
	rm -fr ranzhi/tmp/cache/* 
	rm -fr ranzhi/tmp/extension/*
	rm -fr ranzhi/tmp/log/*
	rm -fr ranzhi/tmp/model/*
	rm -fr ranzhi/tmp/backup/*
	cp -fr www ranzhi && rm -fr ranzhi/www/data/ && mkdir -p ranzhi/www/data/upload
	cp VERSION ranzhi/
	# combine js and css files.
	cp -fr tools ranzhi/tools && cd ranzhi/tools/ && php ./minifyfront.php
	rm -fr ranzhi/tools
	# delete the useless files.
	find ranzhi -name .svn |xargs rm -fr
	find ranzhi -name tests |xargs rm -fr
	for path in `find ranzhi/ -type d`; do touch "$$path/index.html"; done	
	rm ranzhi/www/index.html
	# change mode.
	chmod -R 777 ranzhi/tmp/
	chmod -R 777 ranzhi/www/data
	chmod -R 777 ranzhi/config
	#chmod a+rx ranzhi/bin/*
	#find ranzhi/ -name ext |xargs chmod -R 777
	# zip it.
	zip -rm -9 ranzhi.$(VERSION).zip ranzhi
deb:
	mkdir buildroot
	cp -r build/debian/DEBIAN buildroot
	sed -i '/^Version/cVersion: ${VERSION}' buildroot/DEBIAN/control
	mkdir buildroot/opt
	mkdir buildroot/etc/apache2/sites-enabled/ -p
	cp build/debian/ranzhi.conf buildroot/etc/apache2/sites-enabled/
	cp ranzhi.${VERSION}.zip buildroot/opt
	cd buildroot/opt; unzip ranzhi.${VERSION}.zip; rm ranzhi.${VERSION}.zip
	sed -i 's/index.php/\/ranzhi\/sys\/index.php/' buildroot/opt/ranzhi/www/sys/.htaccess
	sed -i 's/index.php/\/ranzhi\/crm\/index.php/' buildroot/opt/ranzhi/www/crm/.htaccess
	sed -i 's/index.php/\/ranzhi\/cash\/index.php/' buildroot/opt/ranzhi/www/cash/.htaccess
	sed -i 's/index.php/\/ranzhi\/oa\/index.php/' buildroot/opt/ranzhi/www/oa/.htaccess
	sed -i 's/index.php/\/ranzhi\/team\/index.php/' buildroot/opt/ranzhi/www/team/.htaccess
	sudo dpkg -b buildroot/ ranzhi_${VERSION}_1_all.deb
	rm -rf buildroot
rpm:
	mkdir ~/rpmbuild/SPECS -p
	cp build/rpm/ranzhi.spec ~/rpmbuild/SPECS
	sed -i '/^Version/cVersion:${VERSION}' ~/rpmbuild/SPECS/ranzhi.spec
	mkdir ~/rpmbuild/SOURCES
	cp ranzhi.${VERSION}.zip ~/rpmbuild/SOURCES
	mkdir ~/rpmbuild/SOURCES/etc/httpd/conf.d/ -p
	cp build/debian/ranzhi.conf ~/rpmbuild/SOURCES/etc/httpd/conf.d/
	mkdir ~/rpmbuild/SOURCES/opt/ -p
	cd ~/rpmbuild/SOURCES; unzip ranzhi.${VERSION}.zip; mv ranzhi opt/ranzhi;
	sed -i 's/index.php/\/ranzhi\/sys\/index.php/' ~/rpmbuild/SOURCES/opt/ranzhi/www/sys/.htaccess
	sed -i 's/index.php/\/ranzhi\/crm\/index.php/' ~/rpmbuild/SOURCES/opt/ranzhi/www/crm/.htaccess
	sed -i 's/index.php/\/ranzhi\/cash\/index.php/' ~/rpmbuild/SOURCES/opt/ranzhi/www/cash/.htaccess
	sed -i 's/index.php/\/ranzhi\/oa\/index.php/' ~/rpmbuild/SOURCES/opt/ranzhi/www/oa/.htaccess
	sed -i 's/index.php/\/ranzhi\/team\/index.php/' ~/rpmbuild/SOURCES/opt/ranzhi/www/team/.htaccess
	cd ~/rpmbuild/SOURCES; tar -czvf ranzhi-${VERSION}.tar.gz etc opt; rm -rf ranzhi.${VERSION}.zip etc opt;
	rpmbuild -ba ~/rpmbuild/SPECS/ranzhi.spec
	cp ~/rpmbuild/RPMS/noarch/ranzhi-${VERSION}-1.noarch.rpm ./
	rm -rf ~/rpmbuild
