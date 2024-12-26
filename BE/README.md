# Cloud-Cart Backend

This project serves as the backend for the Cloud-Cart e-commerce platform. Developed by ***Juan Martin Palazzolo***, it
provides a REST API for frontend integration and a server-rendered admin panel for administrative tasks

## Features

- PayPal Integration
- REST API (in [Docs](Docs/api_v1.md))
  - FE application
  - Courier communication (shipping)
- Administration Panel

## Requirements

The following programs should be installed in your system:

- Docker (https://www.docker.com/)
- Makefile (https://www.gnu.org/software/make/)
- Php 8.2
- Composer 2.7 (https://getcomposer.org/)
- NodeJS 18 (https://nodejs.org/)
- npm 10 (https://www.npmjs.com/)

### Project Start

1. Fetch the Php & npm dependencies ```make update```
2. Build & run the containers  ```make up```
3. Create the database ```make create_db```
4. Apply the database migrations  ```make apply-migration```
5. Generate default fixtures ```make generate-fixtures```
6. Get inside the Php container ```make bash```
   1. Generate SSL Keys for API ```php bin/console lexik:jwt:generate-keypair```
   2. Generate the Admin account ```php bin/console app:create-admin```

## Commands

- **make up**: Build and run the containers
- **make down**: Stop and remove the containers
- **make xdebug-on**: Enable xdebug (also rebuilds the containers)
- **make xdebug-off**: Disable xdebug (also rebuilds the containers)
- **make cache-clear**: Clear the cache
- **make create-migration**: Create a new migration
- **make apply-migration**: Apply the migrations
- **make ecs**: Run the ECS checks
- **make ecsf**: Fix the ECS errors
- **make phpstan**: Run the PHPStan checks
- **make bash**: Access the PHP container
- **make bash-root**: Access the PHP container as root
