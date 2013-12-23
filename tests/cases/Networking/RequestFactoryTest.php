<?php

namespace Csfd\Networking;

use TestCase;


/**
 * @covers Csfd\Networking\RequestFactory
 */
class RequestFactoryTest extends TestCase
{

	/**
	 * @expectedException Csfd\InternalException
	 */
	public function testUnsetClass()
	{
		$rf = new RequestFactory;
		$rf->create('any url');
	}

	public function testRequestClass()
	{
		$rf = new TestRequestFactory;
		$class = 'MockRequest';
		$rf->setRequestClass($class);

		$this->assertSame(0, TestRequestFactory::$cacheHits);
		$this->assertInstanceOf($class, $rf->create('any url'));
		$this->assertSame(0, TestRequestFactory::$cacheHits);
		$this->assertInstanceOf($class, $rf->create('any url')); // cached
		$this->assertSame(1, TestRequestFactory::$cacheHits);
	}

}

class TestRequestFactory extends RequestFactory
{
	public static $cacheHits = 0;

	protected function getCached($hash)
	{
		$res = parent::getCached($hash);
		if ($res !== NULL)
		{
			self::$cacheHits++;
		}
		return $res;
	}
}
