install:
	composer install

gendiff:
	bin/gendiff ./tests/fixtures/plain/old.json ./tests/fixtures/plain/new.json

validate:
	composer validate

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin

test:
	composer exec --verbose phpunit tests