install:
	composer update
	composer install --no-progress
	mkdir -p db logs
	touch db/db.sqlite
	touch logs/mono.log
	chmod -R 777 db
	chmod -R 777 logs
	mkdir -p assets/node_modules
	npm install ./assets --prefix ./assets/node_modules/
	npm install -g grunt-cli
	grunt --base ./assets --gruntfile ./assets/gruntfile.coffee
	grunt css --base ./assets --gruntfile ./assets/gruntfile.coffee
	grunt vendor --base ./assets --gruntfile ./assets/gruntfile.coffee
	bin/phpmig --init
	bin/phpmig migrate

test:
	mkdir -p logs
	touch logs/test.log
	chmod -R 777 logs/
	bin/phpunit
