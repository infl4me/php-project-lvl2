install:
	composer install

gendiff:
	bin/gendiff ./__fixtures__/file1.json ./__fixtures__/file2.json

validate:
	composer validate

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin