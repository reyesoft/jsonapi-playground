#!/bin/sh

SEARCH_PATHS='./app/ ./bootstrap/*.php ./config/ ./database/ ./tests/ ./routes/'

echo "phpcbf..." &&
./vendor/bin/phpcbf $SEARCH_PATHS

echo "phpcs..." &&
./vendor/bin/phpcs $SEARCH_PATHS

echo "double spaces..." &&
sh resources/bash/find_double_spaces_php.sh

echo "php-cs-fixer..." &&
vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v --path-mode=intersection $SEARCH_PATHS

git status
