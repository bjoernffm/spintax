##
# This file is part of the ChillDev Spintax library.
#
# @author Rafał Wrzeszcz <rafal.wrzeszcz@wrzasq.pl>
# @copyright 2014 © by Rafał Wrzeszcz - Wrzasq.pl.
# @version 0.0.1
# @since 0.0.1
# @package ChillDev\Spintax
##

# environment-vary commands
PHP = $(shell which php)
COMPOSER = $(shell which composer.phar)
PHPDOC = $(shell which phpdoc)
PHPCPD = ./vendor/bin/phpcpd
PHPCS = ./vendor/bin/phpcs
PHPUNIT = ./vendor/bin/phpunit
PHPMD = ./vendor/bin/phpmd

# meta-targets

default: all

all: ci documentation

ci: check lint analyze tests

# project initialization
init:
	git submodule update --init --recursive
	$(COMPOSER) install --optimize-autoloader

# update composer dependencies
update:
	$(COMPOSER) update --optimize-autoloader --no-dev

# syntax checking
check:
	find . -path "./vendor" -prune -o -name "*.php" -exec $(PHP) -l {} \;

# conde linting
lint:
	$(PHPCS) --standard=PSR2 --extensions=php --ignore=vendor,Tests,Resources ./
	$(PHPCPD) --exclude Tests --exclude vendor --exclude Resources .

# static code analyze
analyze:
	$(PHPMD) . text rules.xml --exclude Tests,vendor,Resources

# tests running
tests:
	$(PHPUNIT)
