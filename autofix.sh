#!/bin/sh
php-cs-fixer fix --config=.php_cs.dist -v --path-mode=intersection \
    ./app ./bootstrap/ ./database/ ./resources/ ./tests/
git status
