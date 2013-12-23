#/bin/sh
# vendor/bin/phpcs \
# 	-s \
# 	--ignore=YamlFileLoader \
# 	--report-width=110 \
# 	-p \
# 	--standard=tests/CodingStandard \
# 	--report=full \
# 	src/Csfd \
# && \
phpunit --coverage-html tests/coverage \
	--configuration tests/config.xml \
	--verbose tests/cases/ \
|| \
echo "Failed CS check, tests skipped."

# --report=source \
# --report=gitblame \
