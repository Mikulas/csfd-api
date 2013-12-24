#/bin/sh

# purge old cache
find tests/temp/ -name "*" -type f -mmin +120 -delete

# run cs and phpunit
vendor/bin/phpcs \
	-s \
	--ignore=YamlFileLoader \
	--report-width=110 \
	-p \
	--standard=tests/CodingStandard \
	--report=full \
	src/Csfd \
&& \
phpunit --coverage-html tests/coverage \
	--configuration tests/config.xml \
	--verbose tests/cases/
