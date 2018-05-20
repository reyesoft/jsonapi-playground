#!/bin/sh

echo "php-csniffer..." &&
sh resources/ci/php-csniffer.sh
if [ $? -ne 0 ]; then
    echo "php-csniffer-fix..." &&
    sh resources/ci/php-csniffer-fix.sh
fi

echo "double spaces..." &&
sh resources/ci/tools/find-double-spaces.sh app/
sh resources/ci/tools/find-double-spaces.sh tests/

echo "php-cs-fixer..." &&
./vendor/bin/php-cs-fixer fix --config=resources/ci/.php-cs-fixer.dist

echo "\n 💡  Don't forget to run \n    composer ci-php-md \n"
