APP_NAME=steefdw/airports

include ./.dev/make/main.makefile
include ./.dev/make/validation-php.makefile

## [🔍] Run tests
test:
	./vendor/bin/phpunit -c .dev/config/phpunit.xml

## [🔍] Code Check: run validations
cc: validate test audit

## [🐘]  Run `composer install`
composer:
	composer install --no-interaction --prefer-dist

## 🚀🚀 Run an example
run:
	@php example.php

## 🚀🚀 Update the airports file
update:
	@php update.php
