install-composer :
	curl -sS https://getcomposer.org/installer | php
	mv composer.phar /usr/local/bin/composer

install:
	composer self-update
	composer install
	mkdir -p db logs
	touch db/db.sqlite
	touch logs/mono.log
	chmod -R 777 db
	chmod -R 777 logs
	cd assets/
	mkdir -p assets/node_modules
	npm install ./assets --prefix ./assets/node_modules/
	npm install -g grunt-cli
	grunt --base ./assets --gruntfile ./assets/gruntfile.coffee
	cd ..
	bin/phpmig --init
	bin/phpmig migrate

test:
	mkdir -p logs
	touch logs/test.log
	chmod -R 777 logs/
	bin/phpunit
