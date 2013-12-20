<?php

namespace Foo\Bar\Pax; // tested

use Assert;
use Csfd\Networking\UrlAccess;

require __DIR__ . '/bootstrap.php';

class Tested
{
	use UrlAccess;
}

$t = Access(new Tested);
Assert::same(['bar', 'pax', 'tested'], $t->getConfigKeys());
