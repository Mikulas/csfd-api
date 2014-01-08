<?php

namespace Csfd\Entities;

use TestCase;


class CachingGetterTest extends TestCase
{

	/** @covers \Csfd\Entities\CachingGetter::__call */
	public function testCall_defined()
	{
		$foo = new CGImplement;
		$this->assertSame(0, $foo->callCount);
		$this->assertSame(CGImplement::RETVAL, $foo->getBar());
		$this->assertSame(1, $foo->callCount);
		$this->assertSame(CGImplement::RETVAL, $foo->getBar()); // cached
		$this->assertSame(1, $foo->callCount);
	}

	/**
	 * @covers \Csfd\Entities\CachingGetter::__call
	 * @expectedException \Csfd\InternalException
	 */
	public function testCall_undefined()
	{
		$foo = new CGImplement;
		$this->assertSame(CGImplement::RETVAL, $foo->getUndefined());
	}

	/**
	 * @covers \Csfd\Entities\CachingGetter::__call
	 */
	public function testCall_dynamic()
	{
		$foo = new CGImplementDynamic;
		$this->assertSame('dynamic', $foo->getDynamic());
	}

	/**
	 * @covers \Csfd\Entities\CachingGetter::getPropertyFromGetter
	 */
	public function testGetPropertyFromGetter()
	{
		$e = Access(new CGImplement);
		$this->assertSame('bar', $e->getPropertyFromGetter('getBar'));
	}

	/**
	 * @covers \Csfd\Entities\CachingGetter::setPropertyCache
	 */
	public function testSetPropertyCache()
	{
		$foo = new CGImplement;
		$exp = 'value';
		$foo->setPropertyCache('bar', $exp);
		$this->assertSame(0, $foo->callCount);
		$this->assertSame($exp, $foo->getBar());
		$this->assertSame(0, $foo->callCount);
	}

}

class CGImplement
{
	use CachingGetter;

	const RETVAL = 'value';

	public $callCount = 0;

	protected function _getBar()
	{
		$this->callCount++;
		return self::RETVAL;
	}

}

class CGImplementDynamic
{
	use CachingGetter;

	protected function _get($property)
	{
		return $property;
	}
}
