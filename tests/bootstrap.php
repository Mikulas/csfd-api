<?php

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('Csfd', __DIR__ . '/../src');

if (extension_loaded('xdebug'))
{
	Tester\CodeCoverage\Collector::start(__DIR__ . '/coverage.dat');
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
