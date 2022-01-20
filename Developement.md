# Developement

## Requirements

- Php: >= 7.2.5
- Git: >= 2.0
- Composer: >= 2.1.9

## Install

- Rename the env.dist file to .env

- ### Auto installer

- You can install with one of the install script file located at the base project directory.

### Manually

- clone github package
- install vendors:         `composer install`
- create storage dir:      `mk dir __storage`
- create database:         `php bin/console d:d:c`
- create migration:        `php bin/console make:migration`
- migrate migrations:      `php bin/console d:m:m`
- load fixtures:           `php bin/console doctrine:fixtures:load --group InstallFixtures`

## Write an Issue

- php version
- musicgame version
- description of the issue
- file or class name where is located the fail
- code to reprocuce the fail
- push the issue to the github repository
