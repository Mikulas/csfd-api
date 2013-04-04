<?php

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('Csfd', __DIR__ . '/../src');


if (extension_loaded('xdebug')) {
	Tester\CodeCoverage\Collector::start(__DIR__ . '/coverage.dat');
}
