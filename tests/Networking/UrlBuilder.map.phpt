<?php

use Tester\Assert;
use Csfd\Networking\UrlBuilder;

require __DIR__ . '/bootstrap.php';


$urlBuilder = Access(UrlBuilder::factory(__DIR__ . '/fixtures/valid.yml'));

$urlBuilder->addMap('foo', $value = 'mapped');
Assert::same($value, $urlBuilder->map('{$foo}'));

Assert::exception(function() use ($urlBuilder) {
	$urlBuilder->map('{$foo} {$bar}');
}, 'Csfd\InternalException');
