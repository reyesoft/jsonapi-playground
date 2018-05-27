#!/bin/sh

SEARCH_PATHS_MD='./app/,./config/,./routes/,./tests/,./laravel-json-api/src/'

./vendor/bin/phpmd $SEARCH_PATHS_MD text resources/ci/.phpmd.xml

exit $?
