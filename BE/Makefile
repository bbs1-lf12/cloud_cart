DOCKER_COMPOSE = docker compose
COMPOSE_FILE = ./docker-compose.yml
$DOCKER_ENV =

up:
	INSTALL_XDEBUG=false make build

down:
	$(DOCKER_COMPOSE)  down

build:
	# $(DOCKER_COMPOSE) down -v --remove-orphans
	$(DOCKER_COMPOSE) rm -vsf
	$(DOCKER_COMPOSE) up -d --build

xdebug-on:
	INSTALL_XDEBUG=true make build

xdebug-off:
	INSTALL_XDEBUG=false make build

update:
	composer update
	npm update

cache-clear:
	$(DOCKER_COMPOSE) exec php php bin/console cache:pool:clear --all

test:
	$(DOCKER_COMPOSE) exec php vendor/bin/phpunit ./tests

ecs:
	$(DOCKER_COMPOSE) exec php vendor/bin/ecs

ecsf:
	$(DOCKER_COMPOSE) exec php vendor/bin/ecs --fix

phpstan:
	$(DOCKER_COMPOSE) exec php vendor/bin/phpstan

bash:
	$(DOCKER_COMPOSE) exec -u www-data php bash

bash_root:
	$(DOCKER_COMPOSE) exec -u 0 php bash

create_db:
	$(DOCKER_COMPOSE) exec php php bin/console doctrine:database:create

drop_db:
	$(DOCKER_COMPOSE) exec php php bin/console doctrine:database:drop --force

create-migration:
	$(DOCKER_COMPOSE) exec php php bin/console make:migration

apply-migration:
	$(DOCKER_COMPOSE) exec php php bin/console doctrine:migrations:migrate

generate-ssh-key:
	$(DOCKER_COMPOSE) exec php php bin/console lexik:jwt:generate-keypair

generate-fixtures:
	$(DOCKER_COMPOSE) exec php php bin/console doctrine:fixtures:load
