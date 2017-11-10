#!/bin/sh
./vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v --path-mode=intersection \
    ./app ./bootstrap/ ./database/ ./resources/ ./tests/
sh resources/bash/find_double_spaces_php.sh
git status
