#/bin/sh
rm -rf tests/coverage.dat
vendor/bin/tester \
	-p /usr/local/php5/bin/php \
	-c /usr/local/php5/lib \
	-d zend_extension=/usr/local/php5/lib/php/extensions/no-debug-non-zts-20121212/xdebug.so \
	-d date.timezone=Europe/Prague \
	-s -j 6 ./tests
vendor/bin/coverage-report \
	-c tests/coverage.dat \
	-s src \
	-o tests/coverage.html \
	-t "ČSFD API"
