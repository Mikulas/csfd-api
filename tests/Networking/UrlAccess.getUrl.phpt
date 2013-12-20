<?php

namespace Bar; // tested

use Assert;
use Csfd\Networking\UrlAccess;
use Csfd\Networking\UrlBuilder;

require __DIR__ . '/bootstrap.php';

class Foo
{
	use UrlAccess;
}

$urlBuilder = UrlBuilder::factory(__DIR__ . '/fixtures/valid.yml');
$t = Access(new Foo);

Assert::exception(function() use ($t) {
	$t->getUrl('bar');
}, 'Csfd\InternalException');

$t->setUrlBuilder($urlBuilder);
Assert::same('rootVal/fooBarVal', $t->getUrl('bar'));
