<?php

use Csfd\Entities\CachingGetter;

require __DIR__ . '/../bootstrap.php';

covers('Csfd\Entities\CachingGetter');

class Foo
{
	use CachingGetter;

	const RETVAL = 'value';

	public static $callCount = 0;

	protected function _getBar()
	{
		self::$callCount++;
		return self::RETVAL;
	}
}

$foo = new Foo;

Assert::exception(function() use ($foo) {
	$foo->getNonExistent();
}, 'Csfd\InternalException');

Assert::same(0, Foo::$callCount);
Assert::same(Foo::RETVAL, $foo->getBar());
Assert::same(1, Foo::$callCount);
Assert::same(Foo::RETVAL, $foo->getBar()); // cached
Assert::same(1, Foo::$callCount);
