#/bin/sh
php vendor/bin/phpcs --report-width=110 -p --standard=tests/CodingStandard --report=full --report=source --report=gitblame -a src/Csfd \
&& \
phpunit --coverage-html tests/coverage --configuration tests/config.xml --verbose tests/cases/
