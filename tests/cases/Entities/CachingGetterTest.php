<?php

use Csfd\Entities\CachingGetter;


class CachingGetterTest extends TestCase
{

	/** @covers \Csfd\Entities\CachingGetter::__call */
	public function testCallDefined()
	{
		$foo = new Foo;
		$this->assertSame(0, Foo::$callCount);
		$this->assertSame(Foo::RETVAL, $foo->getBar());
		$this->assertSame(1, Foo::$callCount);
		$this->assertSame(Foo::RETVAL, $foo->getBar()); // cached
		$this->assertSame(1, Foo::$callCount);
	}

	/**
	 * @covers \Csfd\Entities\CachingGetter::__call
	 * @expectedException \Csfd\InternalException
	 */
	public function testCallUndefined()
	{
		$foo = new Foo;
		$this->assertSame(Foo::RETVAL, $foo->getUndefined());
	}

	/**
	 * @covers \Csfd\Entities\CachingGetter::__call
	 */
	public function testCallDynamic()
	{
		$foo = new FooDynamic;
		$this->assertSame('dynamic', $foo->getDynamic());
	}

}

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

class FooDynamic
{
	use CachingGetter;

	protected function _get($property)
	{
		return $property;
	}
}
