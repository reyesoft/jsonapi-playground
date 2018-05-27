#!/bin/sh

./vendor/bin/phpcpd --min-tokens=50 ./app/ ./bootstrap/*.php  ./config/ ./routes/ ./laravel-json-api/src/ \
--regexps-exclude=IntegrationTests \
--names-exclude=FiscalbookExport.php,FiscalbookTrait.php \
&&

exit $?
