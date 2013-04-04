<?php

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../Csfd/helper.php';
require __DIR__ . '/../Csfd/author.php';
require __DIR__ . '/../Csfd/movie.php';
require __DIR__ . '/../Csfd/grabber.php';
require __DIR__ . '/../Csfd/search.php';
require __DIR__ . '/../Csfd/csfd.php';


if (extension_loaded('xdebug')) {
	Tester\CodeCoverage\Collector::start(__DIR__ . '/coverage.dat');
}
