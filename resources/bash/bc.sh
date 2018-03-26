#!/bin/sh

SEARCH_PATHS='./app ./bootstrap/*.php ./database/ ./tests/'

### BEAUTIFUL CODE <3
sh resources/bash/find_double_spaces_php.sh &&
./vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v \
    --dry-run --stop-on-violation --using-cache=no --path-mode=intersection\
    $SEARCH_PATHS &&
exit $?
