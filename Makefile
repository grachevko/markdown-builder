all: composer-normalize php-cs-fixer phpstan phpunit readme

readme:
	php bin/readme.php

composer-normalize:
	composer normalize --dry-run

php-cs-fixer:
	vendor/bin/php-cs-fixer fix $(if $(DRY),--dry-run) $(if $(DEBUG),-vvv)

phpstan:
	vendor/bin/phpstan analyse --no-interaction --memory-limit=-1 --configuration=phpstan.neon

phpunit:
	vendor/bin/phpunit --stop-on-failure