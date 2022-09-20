#!/bin/bash

DOCKER_CONTAINER = app
DB_CONTAINER = db
DB_PORT = 33406
OS := $(shell uname)

ifeq ($(OS),Darwin)
    UID = $(shell id -u)
else ifeq ($(OS),Linux)
	UID = $(shell id -u)
else
	UID = 1000
endif

run: ## Start the containers in detached mode (background)
	U_ID=${UID} docker-compose up -d

run-attached: ## Start the containers in attached mode
	U_ID=${UID} docker-compose up

stop: ## Stop the containers
	U_ID=${UID} docker-compose stop

down: ## Stop the containers
	U_ID=${UID} docker-compose down --remove-orphans

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) run

build: ## Rebuilds all the containers
	U_ID=${UID} docker-compose build

prepare: ## Runs backend commands
	$(MAKE) composer-install
	$(MAKE) check-dot-env
	$(MAKE) generate-application-key
	$(MAKE) generate-storage-link
	$(MAKE) fix-perms
	$(MAKE) migrate-db
	$(MAKE) npm-install
	$(MAKE) npm-dev-compile-js-css

composer-install: ## Installs composer dependencies
	U_ID=${UID} docker-compose exec --user ${UID} ${DOCKER_CONTAINER} composer install --no-scripts --no-interaction --optimize-autoloader

check-dot-env: ## Check if .env exists and if not create it
	if [ ! -f ".env" ] && [ -f ".env.example" ]; then cp .env.example .env; else echo ".env already exists or .env.example doesn't exist"; exit 0; fi

generate-application-key: ## Generate laravel application key
	U_ID=${UID} docker-compose exec --user ${UID} ${DOCKER_CONTAINER} php artisan key:generate

generate-storage-link: ## Generate storage soft link
	U_ID=${UID} docker-compose exec --user ${UID} ${DOCKER_CONTAINER} php artisan storage:link

fix-perms: ## Fix folders permissions
	sudo chown -R ${USER}:www-data storage/ bootstrap/cache/
	sudo chmod -R 775 storage/ bootstrap/cache/

migrate-db: ## Migrate and seed app
	U_ID=${UID} docker-compose exec --user ${UID} ${DOCKER_CONTAINER} php artisan migrate:refresh --seed

npm-install: ## Installs npm dependencies
	U_ID=${UID} docker-compose exec --user ${UID} ${DOCKER_CONTAINER} npm i

npm-dev-compile-js-css: ## Compile all css and js files
	U_ID=${UID} docker-compose exec --user ${UID} ${DOCKER_CONTAINER} npm run dev

update-dependencies: ## Updates dependencies
	U_ID=${UID} docker-compose exec --user ${UID} ${DOCKER_CONTAINER} composer update;
	U_ID=${UID} docker-compose exec --user ${UID} ${DOCKER_CONTAINER} npm update;

bash: ## ssh's into the be container
	U_ID=${UID} docker-compose exec --user ${UID} ${DOCKER_CONTAINER} bash

test: ##run the tests
	U_ID=${UID} docker-compose exec --user ${UID} ${DOCKER_CONTAINER} php artisan test
