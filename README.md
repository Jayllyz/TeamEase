<h1 align="center"> TeamEase Project </h1>
<h2 align="center">Together & Stronger </h2>

<p align="center">
  ESGI 2i final project	
</p>

## Table of Contents

- [Table of Contents](#table-of-contents)
- [Introduction](#introduction)
- [Tech Stack](#tech-stack)
- [Local development](#local-development)
  - [Web, database \& API](#web-database--api)
  - [Android](#android)
  - [Java](#java)
  - [Bot C](#bot-c)
- [Contributors](#contributors)

## Introduction

Final project of the 2i year at ESGI. The goal of this project is to computerize a team building company.

The scope of this project includes the development of the following components:

- Web application and API
- FAQ Bot
- Java dashboard
- Android application
- Network architecture

## Tech Stack

| Technology | Languages/Frameworks                      |
| ---------- | ----------------------------------------- |
| Web        | PHP 8.2+, Javascript, HTML, CSS, Boostrap |
| Android    | Java, Volley                              |
| Bot        | C, PHP                                    |
| Dashboard  | Java, JavaFX, JFreeChart                  |
| Database   | MySQL                                     |
| Deployment | Docker, Docker-compose, Maven             |
| API        | INSEE, reCAPTCHA, Stripe                  |

## Local development

### Web, database & API

```bash
# clone the repo
git clone

# create .env file
#PHP
PHP_PORT= # 80 or 8080
ADMINER_PORT= # 8081 or 8082
PHP_USER_HOME_DIRECTORY=/home/php

#DB
MYSQL_PORT= # 3306 or 3307
MYSQL_ROOT_PASSWORD= #password
MYSQL_DATABASE= #dbname

#TOKEN & CREDENTIALS
EMAIL= #email
EMAIL_PASSWORD= #password
TOKEN_INSEE= #insee token
CAPTCHA_SITE= #captcha site key
CAPTCHA_SECRET= #captcha secret key
STRIPE_PUBLIC= #stripe public key
STRIPE_SECRET = #stripe secret key

# Start development Docker environment
docker compose -f "docker-compose.dev.yml" up -d --build

# Download composer dependencies
docker exec -it php composer install
```

### Android

You can find `togetherandstronger.apk` in the folder `android/`

### Java

You can install the dashboard with `Dashboard.exe` in the folder `executable/`

### Bot C

```bash
# Run makefile
make all
```

## Contributors

- [@Jayllyz](https://github.com/Jayllyz) - DevOps & Backend
- [@userMeh](https://github.com/userMeh) - Fullstack
- [@minatoco](https://github.com/minatoco) - Network
