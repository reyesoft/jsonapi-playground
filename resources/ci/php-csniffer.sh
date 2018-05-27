#!/bin/sh

SEARCH_PATHS='./app/ ./bootstrap/*.php ./config/ ./database/ ./routes/ ./tests/ ./laravel-json-api/src/'

./vendor/bin/phpcs --cache --standard=resources/ci/.php-csniffer.xml $SEARCH_PATHS &&

exit $?
