install:
	composer self-update
	composer install --no-progress
	mkdir -p db
	touch db/db.sqlite
	touch logs/mono.log
	chmod -R 777 db
	chmod -R 777 logs
	npm install ./assets
	cd assets/
	grunt --base ./assets --gruntfile ./assets/gruntfile.coffee
	bin/phpmig --init
	bin/phpmig migrate

test:
	mkdir -p logs
	touch logs/test.log
	chmod -R 777 logs/
	bin/phpunit
