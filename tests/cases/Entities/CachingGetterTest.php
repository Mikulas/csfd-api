<?php

namespace Csfd\Entities;

use TestCase;


class CachingGetterTest extends TestCase
{

	/** @covers \Csfd\Entities\CachingGetter::__call */
	public function testCall_defined()
	{
		$foo = new CGImplement;
		$this->assertSame(0, CGImplement::$callCount);
		$this->assertSame(CGImplement::RETVAL, $foo->getBar());
		$this->assertSame(1, CGImplement::$callCount);
		$this->assertSame(CGImplement::RETVAL, $foo->getBar()); // cached
		$this->assertSame(1, CGImplement::$callCount);
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

}

class CGImplement
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

class CGImplementDynamic
{
	use CachingGetter;

	protected function _get($property)
	{
		return $property;
	}
}
