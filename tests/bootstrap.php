<?php

use Csfd\CodeCoverage\Collector;

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('Csfd', __DIR__ . '/../src');
$loader->add('', __DIR__ . '/mocks');

require __DIR__ . '/Collector.php';

if (extension_loaded('xdebug'))
{
	Collector::start(__DIR__ . '/coverage.dat');
}

// assumes PSR-0
function covers($class)
{
	$file = str_replace('\\', '/', $class) . '.php';
	Collector::$coveredFiles[] = realpath(__DIR__ . "/../src/$file");
}

$urlsPath = __DIR__ . '/../src/Csfd/urls.yml';

class Assert extends Tester\Assert
{

	public static function exception($function, $class, $code = NULL)
	{
		$e = parent::exception($function, $class);
		if ($code)
		{
			self::same($code, $e->getCode());
		}
	}

}

Tester\Helpers::setup();
