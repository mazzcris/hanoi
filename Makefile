test:
	vendor/bin/phpstan analyse -c ./phpstan.neon
	vendor/bin/phpunit -c ./phpunit.xml --colors --display-phpunit-deprecations $(if $(strip $(filter)),--filter=$(filter),)

new:
	curl -c cookies.txt http://localhost:8000/new

state:
	curl -b cookies.txt http://localhost:8000/state

move:
	curl -X POST -b cookies.txt http://localhost:8000/move/$(word 2,$(MAKECMDGOALS))/$(word 3,$(MAKECMDGOALS))

%:
	@: