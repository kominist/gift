install:
	rm -r vendor
	composer status
	composer self-update
	composer install
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

documentation :
	npm install -g yuidocjs
	yuidoc assets/dist/js -o docs/frontend/
	./bin/phpdoc.php -d app/ -t ./docs/backend/

update :
	composer update

test:
	mkdir -p logs
	touch logs/test.log
	chmod -R 777 logs/
	bin/phpunit
	npm install ./tests/frontend --prefix ./tests/frontend/node_modules/
	npm install -g phantomjs
	npm install -g mocha-phantomjs
	grunt --base ./tests/frontend --gruntfile ./tests/frontend/gruntfile.coffee
	mocha-phantomjs /tests/frontend/testRunner.html
