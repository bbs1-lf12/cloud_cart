up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose down -v --remove-orphans
	docker compose rm -vsf
	docker compose up -d --build

test:
	docker compose exec php vendor/bin/phpunit ./tests

ecs:
	docker compose exec php vendor/bin/ecs

ecsf:
	docker compose exec php vendor/bin/ecs --fix

phpstan:
	docker compose exec php vendor/bin/phpstan

bash:
	docker compose exec -u www-data php bash

bash_root:
	docker compose exec -u 0 php bash

create_db:
	docker compose exec php php bin/console doctrine:database:create

drop_db:
	docker compose exec php php bin/console doctrine:database:drop --force

create-migration:
	docker compose exec php php bin/console make:migration

apply-migration:
	docker compose exec php php bin/console doctrine:migrations:migrate
