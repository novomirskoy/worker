test:
	composer test

coverage:
	composer coverage

lint:
	composer lint
	composer php-cs-fixer fix -- --dry-run --diff

cs-fix:
	composer php-cs-fixer fix

clear-cache:
	rm -Rf var/cache
