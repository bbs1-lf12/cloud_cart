# LF12 Project

## Project Description

This project is an online store backend developed by ***Juan Martin Palazzolo*** that provides an API to support various types of frontend applications.

## Features

- **Easy project startup** via CLI commands
- **User-friendly cart management** for customers
- **Seamless purchase process** for articles
- **Integrated PayPal payment gateway** for secure transactions
- **Dynamic data filtering** in tables for improved usability
- **Comprehensive administration** of articles, orders and users


## Requirements

The following programs should be installed in your system:

- Docker (https://www.docker.com/)
- Makefile (https://www.gnu.org/software/make/)
- Php 8.2
- Composer 2.7 (https://getcomposer.org/)
- NodeJS 18 (https://nodejs.org/)
- npm 10 (https://www.npmjs.com/)

### Project Start
1. Fetch the Php dependencies ```composer install```
2. Fetch the npm dependencies ```npm build```
3. Build & run the containers  ```make build```
4. Create the database ```make create_db```
5. Apply the database migrations  ```make apply-migration```
6. Generate SSL Keys for API ```php bin/console lexik:jwt:generate-keypair```
7. Generate the Admin account ```php bin/console app:create-admin```

# FAQ
- Does xdebug not work?
  - Check if the port is open 9003 on the host machine
