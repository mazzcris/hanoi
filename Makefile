test:
	vendor/bin/phpstan analyse -c ./phpstan.neon
	vendor/bin/phpunit -c ./phpunit.xml --colors --display-phpunit-deprecations $(if $(strip $(filter)),--filter=$(filter),)