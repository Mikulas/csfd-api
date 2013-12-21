<?php

namespace Foo\Bar\Pax; // tested

use Assert;
use Csfd\Networking\UrlAccess;

require __DIR__ . '/bootstrap.php';

covers('Csfd\Networking\UrlAccess');

class Foo
{
	use UrlAccess;
}

$t = Access(new Foo);
Assert::same(['bar', 'pax', 'foo'], $t->getConfigKeys());
