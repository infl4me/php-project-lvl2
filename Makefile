install:
	composer install

gendiff:
	bin/gendiff --format stylish ./tests/fixtures/nested/old.json ./tests/fixtures/nested/new.json

validate:
	composer validate

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin

test:
	composer exec --verbose phpunit tests